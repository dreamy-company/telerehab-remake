<?php

namespace App\Livewire\Auth;

use App\Mail\VerifyEmailMail;
use App\Models\Country;
use App\Models\Patient;
use App\Models\PatientPhoto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.guest')]
class Register extends Component
{
    use WithFileUploads;

    // State Management Steps
    public $currentStep = 1;
    public $totalSteps = 3;

    // Data Properties
    public $name, $email, $password, $countryId, $telephone; // Step 1
    public $address, $medical_record_number, $prosthetic, $prosthetic_since; // Step 2
    public $bpjs_number, $bpjs_card; // Step 3

    // Ubah menjadi array untuk support multiple upload sesuai snippet Anda
    public $patient_condition = [];

    // Judul Step untuk UI
    public function getStepTitleProperty()
    {
        return match ($this->currentStep) {
            1 => 'Create New Account',
            2 => 'Medical Information',
            3 => 'Supporting Documents',
        };
    }

    public function getStepDescriptionProperty()
    {
        return match ($this->currentStep) {
            1 => 'Complete your personal and contact information.',
            2 => 'Provide address and prosthetic requirements.',
            3 => 'Upload condition photos and BPJS card.',
        };
    }

    // Navigasi Next
    public function nextStep()
    {
        $this->validateCurrentStep();
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    // Navigasi Previous
    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    // Validasi Per Step
    public function validateCurrentStep()
    {
        if ($this->currentStep == 1) {
            // Country & telephone are optional for now: coerce blanks to null so the
            // `nullable` rules short-circuit numeric/digits/unique checks when empty.
            $this->telephone = filled($this->telephone) ? $this->telephone : null;
            $this->countryId = filled($this->countryId) ? $this->countryId : null;

            $this->validate([
                'name' => 'required|string|min:3',
                'email' => 'required|email|unique:users,email',
                'countryId' => 'nullable|exists:countries,id',
                'telephone' => 'nullable|numeric|digits_between:7,15|unique:users,telephone',
                'password' => 'required|min:6',
            ]);

        } elseif ($this->currentStep == 2) {
            $this->validate([
                'address' => 'nullable|string|min:10',
                'medical_record_number' => 'nullable|string|unique:patients,medical_record_number',
                'prosthetic' => 'nullable|string',
                'prosthetic_since' => 'nullable|date',
            ]);
        }
    }

    // Fungsi Utama: Register (Menggantikan Store)
    public function register()
    {
        
        // 1. Validasi Step Terakhir (Step 3)
        $this->validate([
            'bpjs_number' => 'nullable|unique:patients,bpjs_number',
            'bpjs_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'patient_condition' => 'nullable|array', // Validasi array
            'patient_condition.*' => 'file|mimes:jpg,jpeg,png|max:2048', // Validasi tiap file
        ]);

        try {
            // 2. Buat User
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'country_id' => $this->countryId,
                'telephone' => $this->telephone ?? '', // column is NOT NULL; store '' when blank
                'role' => 'patient',
            ]);

            // 3. Upload BPJS (Jika ada)
            $bpjsPath = null;
            if ($this->bpjs_card) {
                $bpjsPath = $this->bpjs_card->store('bpjs', 'public');
            }

            // 4. Buat Patient Profile
            $patient = Patient::create([
                'user_id' => $user->id,
                'medical_record_number' => $this->medical_record_number,
                'bpjs_number' => $this->bpjs_number,
                'bpjs_card' => $bpjsPath,
                'prosthetic' => $this->prosthetic,
                'prosthetic_since' => $this->prosthetic_since,
                'address' => $this->address,
            ]);

            // 5. Upload Foto Kondisi Pasien (Multiple Loop)
            if (!empty($this->patient_condition)) {
                foreach ($this->patient_condition as $file) {
                    $photoPath = $file->store('patient_photos', 'public');
                    PatientPhoto::create([
                        'patient_id' => $patient->id,
                        'url' => $photoPath,
                    ]);
                }
            }
            
            // 7. Redirect to verify email page with success message
            return redirect()->route('auth.verification', encrypt($user->email))->with('success-alert', 'Registration successful! Please verify your email.');
        } catch (ValidationException $e) {
            $this->dispatch('alert-error', collect($e->errors())->flatten()->first());
        } catch (\Exception $e) {
            $this->dispatch('alert-error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.register', [
            'countries' => Country::orderBy('name')
                ->get(['id', 'name', 'code'])
                ->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'iso2' => strtolower($c->code)])
                ->values()
                ->toArray(),
        ]);
    }
}
