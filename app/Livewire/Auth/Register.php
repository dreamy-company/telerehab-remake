<?php

namespace App\Livewire\Auth;

use App\Models\Patient;
use App\Models\PatientPhoto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Register extends Component
{
    use WithFileUploads;
    public $name, $email, $password, $telephone, $medical_record_number, $bpjs_number, $bpjs_card, $patient_condition = [], $address, $prosthetic, $prosthetic_since;   

    public function render()
    {
        return view('livewire.auth.register');
    }

    public function store()
    {


        try {
            $this->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'telephone' => 'required',
                'medical_record_number' => 'required|unique:patients,medical_record_number',
                'bpjs_number' => 'required|unique:patients,bpjs_number',
                'bpjs_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'patient_condition' => 'required',
                'patient_condition.*' => 'file|mimes:jpg,jpeg,png|max:2048',
                'address' => 'required',
                'prosthetic' => 'required|string',
                'prosthetic_since' => 'required|date',
            ]);

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'telephone' => $this->telephone,
                'role' => 'patient',
            ]);

            $bpjsPath = null;
            if ($this->bpjs_card) {
                $bpjsPath = $this->bpjs_card->store('bpjs', 'public');
            }

            $patient = Patient::create([
                'user_id' => $user->id,
                'medical_record_number' => $this->medical_record_number ?? null,
                'bpjs_number' => $this->bpjs_number ?? null,
                'bpjs_card' => $bpjsPath,
                'prosthetic' => $this->prosthetic ?? null,
                'prosthetic_since' => $this->prosthetic_since ?? null,
                'address' => $this->address,
            ]);

            if ($this->patient_condition) {
                foreach ($this->patient_condition as $file) {
                    $photoPath = $file->store('patient_photos', 'public');
                    PatientPhoto::create([
                        'patient_id' => $patient->id,
                        'url' => $photoPath,
                    ]);
                }
            }

            return redirect()->route('auth.login')->with('success-alert', 'Registration successful. Please log in.');
            
        } catch (ValidationException $e) {
            $this->dispatch('alert-error', collect($e->errors())->flatten()->first());
        }
    }
}
