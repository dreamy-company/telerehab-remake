<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientPhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
                'telephone'  => $user->telephone,
                'country'    => $user->country?->name,
                'country_id' => $user->country_id,
                'role'       => $user->role,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            // Step 1
            'name'      => 'required|string|min:3',
            'email'     => 'required|email|unique:users,email',
            'country_id' => 'required|exists:countries,id',
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
            'country_id' => $request->country_id,
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

        // Kirim email verifikasi jika perlu
        // $user->sendEmailVerificationNotification();

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

    public function profile(Request $request)
    {
        $user    = $request->user()->load('patient');
        $patient = $user->patient;

        return response()->json([
            'user' => [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'telephone'  => $user->telephone,
                'country'    => $user->country?->name,
                'country_id' => $user->country_id,
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
}
