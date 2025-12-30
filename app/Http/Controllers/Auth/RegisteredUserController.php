<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientPhoto;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'telephone' => 'required',
            'medical_record_number' => 'nullable|unique:patients,medical_record_number',
            'bpjs_number' => 'nullable|unique:patients,bpjs_number',
            'bpjs_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'patient_photo' => 'nullable',
            'patient_photo.*' => 'file|mimes:jpg,jpeg,png|max:2048',
            'address' => 'required',
            'prosthetic' => 'nullable|string',
            'prosthetic_since' => 'nullable|date',
        ]);

        try {


            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'telephone' => $validatedData['telephone'],
                'role' => 'patient',
            ]);

            $bpjsPath = null;
            if ($request->hasFile('bpjs_card')) {
                $bpjsPath = $request->file('bpjs_card')->store('bpjs', 'public');
            }

            $patient = Patient::create([
                'user_id' => $user->id,
                'medical_record_number' => $validatedData['medical_record_number'] ?? null,
                'bpjs_number' => $validatedData['bpjs_number'] ?? null,
                'bpjs_card' => $bpjsPath,
                'prosthetic' => $validatedData['prosthetic'] ?? null,
                'prosthetic_since' => $validatedData['prosthetic_since'] ?? null,
                'address' => $validatedData['address'],
            ]);

            if ($request->hasFile('patient_photo')) {
                foreach ($request->file('patient_photo') as $file) {
                    $photoPath = $file->store('patient_photos', 'public');
                    PatientPhoto::create([
                        'patient_id' => $patient->id,
                        'url' => $photoPath,
                    ]);
                }
            }



            event(new Registered($user));
            Auth::login($user);
            $request->session()->regenerate();
        } catch (\Throwable $e) {
            dd($e);
            throw $e;
        }

        return response()->noContent();
    }
}
