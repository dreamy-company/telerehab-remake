<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmailMail;
use App\Models\Patient;
use App\Models\PatientPhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not registered.'], 404);
        }

        if (is_null($user->email_verified_at)) {
            return response()->json(['message' => 'Email not verified. Please verify your email before logging in.'], 403);
        }

        if ($user->role !== 'patient') {
            return response()->json(['message' => 'Access denied. Not a patient account.'], 403);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid password.'], 401);
        }

        $token = $user->createToken('patient-mobile')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token'   => $token,
            'user'    => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'telephone'=> $user->telephone,
                'country'  => $user->country,
                'role'     => $user->role,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            // Step 1
            'name'      => 'required|string|min:3',
            'email'     => 'required|email|unique:users,email',
            'country'   => 'required|string',
            'telephone' => 'required|numeric|unique:users,telephone',
            'password'  => 'required|min:6',
            // Step 2
            'address'               => 'nullable|string|min:10',
            'medical_record_number' => 'nullable|string|unique:patients,medical_record_number',
            'prosthetic'            => 'nullable|string',
            'prosthetic_since'      => 'nullable|date',
            // Step 3
            'bpjs_number'       => 'nullable|unique:patients,bpjs_number',
            'bpjs_card'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'patient_condition' => 'nullable|array',
            'patient_condition.*' => 'file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'country'   => $request->country,
            'telephone' => $request->telephone,
            'role'      => 'patient',
        ]);

        $bpjsPath = null;
        if ($request->hasFile('bpjs_card')) {
            $bpjsPath = $request->file('bpjs_card')->store('bpjs', 'public');
        }

        $patient = Patient::create([
            'user_id'               => $user->id,
            'medical_record_number' => $request->medical_record_number,
            'bpjs_number'           => $request->bpjs_number,
            'bpjs_card'             => $bpjsPath,
            'prosthetic'            => $request->prosthetic,
            'prosthetic_since'      => $request->prosthetic_since,
            'address'               => $request->address,
        ]);

        if ($request->hasFile('patient_condition')) {
            foreach ($request->file('patient_condition') as $file) {
                $photoPath = $file->store('patient_photos', 'public');
                PatientPhoto::create([
                    'patient_id' => $patient->id,
                    'url'        => $photoPath,
                ]);
            }
        }

        // Kirim email verifikasi (signed route, sama seperti flow web)
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id'   => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        Mail::to($user->email)->send(
            new VerifyEmailMail($url, $user->name)
        );

        return response()->json([
            'message' => 'Registration successful. Please verify your email.',
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function profile(Request $request, $id = null)
    {
        // {id} adalah id user — pasien hanya boleh melihat profilnya sendiri
        if ($id !== null && (int) $id !== (int) $request->user()->id) {
            return response()->json(['message' => 'Access denied. You can only view your own profile.'], 403);
        }

        $user    = $request->user()->load('patient');
        $patient = $user->patient;

        return response()->json([
            'user' => [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'telephone' => $user->telephone,
                'country'   => $user->country,
            ],
            'patient' => $patient ? [
                'id'                    => $patient->id,
                'medical_record_number' => $patient->medical_record_number,
                'bpjs_number'           => $patient->bpjs_number,
                'bpjs_card_url'         => $patient->bpjs_card ? asset('storage/' . $patient->bpjs_card) : null,
                'prosthetic'            => $patient->prosthetic,
                'prosthetic_since'      => $patient->prosthetic_since,
                'address'               => $patient->address,
            ] : null,
        ]);
    }

    public function updateProfile(Request $request, $id)
    {
        $user = $request->user();

        // {id} adalah id user — pasien hanya boleh mengubah profilnya sendiri
        if ((int) $id !== (int) $user->id) {
            return response()->json(['message' => 'Access denied. You can only update your own profile.'], 403);
        }

        $patient = $user->patient;

        if (!$patient) {
            return response()->json(['message' => 'Patient profile not found.'], 404);
        }

        $request->validate([
            'name'      => 'sometimes|required|string|min:3',
            'email'     => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'country'   => 'sometimes|required|string',
            'telephone' => ['sometimes', 'required', 'numeric', Rule::unique('users', 'telephone')->ignore($user->id)],
            'password'  => 'nullable|min:6',

            'address'               => 'nullable|string|min:10',
            'medical_record_number' => ['nullable', 'string', Rule::unique('patients', 'medical_record_number')->ignore($patient->id)],
            'prosthetic'            => 'nullable|string',
            'prosthetic_since'      => 'nullable|date',
            'bpjs_number'           => ['nullable', Rule::unique('patients', 'bpjs_number')->ignore($patient->id)],
            'bpjs_card'             => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'patient_condition'     => 'nullable|array',
            'patient_condition.*'   => 'file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update data user (hanya field yang dikirim)
        foreach (['name', 'email', 'country', 'telephone'] as $field) {
            if ($request->has($field)) {
                $user->{$field} = $request->input($field);
            }
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Ganti kartu BPJS (hapus file lama, simpan file baru)
        if ($request->hasFile('bpjs_card')) {
            if ($patient->bpjs_card && Storage::disk('public')->exists($patient->bpjs_card)) {
                Storage::disk('public')->delete($patient->bpjs_card);
            }

            $patient->bpjs_card = $request->file('bpjs_card')->store('bpjs', 'public');
        }

        // Update data pasien (hanya field yang dikirim)
        foreach (['medical_record_number', 'bpjs_number', 'prosthetic', 'prosthetic_since', 'address'] as $field) {
            if ($request->has($field)) {
                $patient->{$field} = $request->input($field);
            }
        }

        $patient->save();

        // Ganti foto kondisi pasien (hapus foto lama di db + storage, sama seperti flow web)
        if ($request->hasFile('patient_condition')) {
            PatientPhoto::where('patient_id', $patient->id)->each(function ($photo) {
                if (Storage::disk('public')->exists($photo->url)) {
                    Storage::disk('public')->delete($photo->url);
                }
                $photo->delete();
            });

            foreach ($request->file('patient_condition') as $file) {
                PatientPhoto::create([
                    'patient_id' => $patient->id,
                    'url'        => $file->store('patient_photos', 'public'),
                ]);
            }
        }

        $patient->refresh();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'telephone' => $user->telephone,
                'country'   => $user->country,
            ],
            'patient' => [
                'id'                    => $patient->id,
                'medical_record_number' => $patient->medical_record_number,
                'bpjs_number'           => $patient->bpjs_number,
                'bpjs_card_url'         => $patient->bpjs_card ? asset('storage/' . $patient->bpjs_card) : null,
                'prosthetic'            => $patient->prosthetic,
                'prosthetic_since'      => $patient->prosthetic_since,
                'address'               => $patient->address,
            ],
        ]);
    }
}
