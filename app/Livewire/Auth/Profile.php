<?php

namespace App\Livewire\Auth;

use App\Models\Country;
use App\Models\Patient;
use App\Models\PatientPhoto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class Profile extends Component
{
    use WithFileUploads;
    public $userId, $patientId, $userData, $name, $email, $countryId, $telephone, $password, $medical_record_number, $bpjs_number, $bpjs_card, $patient_condition = [], $address, $prosthetic, $prosthetic_since, $old_patient_condition, $old_bpjs_card;

    public function mount($id = null)
    {
        $role = Auth::user()->role;

        if ($role === 'patient') {
            $patient = Auth::user()->patient;
            $this->patientId = $patient->id;
            $this->userId = Auth::user()->id;
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
            $this->countryId = Auth::user()->country_id;
            $this->telephone = Auth::user()->telephone;
            $this->medical_record_number = $patient->medical_record_number;
            $this->bpjs_number = $patient->bpjs_number;
            $this->address = $patient->address;
            $this->prosthetic = $patient->prosthetic;
            $this->prosthetic_since = $patient->prosthetic_since;
            $this->old_bpjs_card = $patient->bpjs_card;
            $this->old_patient_condition = $patient->photos;
        } else {
            $this->userId = decrypt($id);
            $userData = User::find($this->userId);
            $this->name = $userData->name;
            $this->email = $userData->email;
            $this->countryId = $userData->country_id;
            $this->telephone = $userData->telephone;
        }
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|max:255|unique:users,email,{$this->userId}",
            'countryId' => 'required|exists:countries,id',
            'telephone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::find($this->userId);
        $user->name = $this->name;
        $user->email = $this->email;
        $user->country_id = $this->countryId;
        $user->telephone = $this->telephone;

        if (!empty($this->password)) {
            $user->password = bcrypt($this->password);
        }

        $user->save();

        return redirect()->route(Auth::user()->role . '.dashboard')->with('success-alert', 'Profile updated successfully.');
    }

    public function updatePatient()
    {
        try {
            $this->validate([
                'name' => 'required',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($this->userId),
                ],
                'password' => $this->patientId
                    ? 'nullable|min:6'
                    : 'required|min:6',

                'telephone' => 'required',
                'countryId' => 'required|exists:countries,id',

                'medical_record_number' => [
                    'required',
                    Rule::unique('patients', 'medical_record_number')->ignore($this->patientId),
                ],
                'bpjs_number' => [
                    'required',
                    Rule::unique('patients', 'bpjs_number')->ignore($this->patientId),
                ],

                'bpjs_card' => $this->old_bpjs_card
                    ? 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
                    : 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',

                'patient_condition' => !empty($this->old_patient_condition)
                    ? 'nullable|array'
                    : 'required|array',
                'patient_condition.*' => 'file|mimes:jpg,jpeg,png|max:2048',
                'address' => 'required',
                'prosthetic' => 'required|string',
                'prosthetic_since' => 'required|date',
            ]);

            /** =========================
             * USER (updateOrCreate)
             * ========================= */
            $user = User::updateOrCreate(
                ['id' => $this->userId],
                [
                    'name' => $this->name,
                    'email' => $this->email,
                    'country_id' => $this->countryId,
                    'telephone' => $this->telephone,
                    'role' => 'patient',
                    'password' => $this->password
                        ? Hash::make($this->password)
                        : User::find($this->userId)?->password,
                ]
            );

            /** =========================
             * FILE BPJS
             * ========================= */
            $bpjsPath = $this->old_bpjs_card;

            if ($this->bpjs_card instanceof TemporaryUploadedFile) {

                // hapus file lama
                if ($this->old_bpjs_card && Storage::disk('public')->exists($this->old_bpjs_card)) {
                    Storage::disk('public')->delete($this->old_bpjs_card);
                }

                // simpan file baru
                $bpjsPath = $this->bpjs_card->store('bpjs', 'public');
            }

            /** =========================
             * PATIENT (updateOrCreate)
             * ========================= */
            $patient = Patient::updateOrCreate(
                ['id' => $this->patientId],
                [
                    'user_id' => $user->id,
                    'medical_record_number' => $this->medical_record_number,
                    'bpjs_number' => $this->bpjs_number,
                    'bpjs_card' => $bpjsPath ?? Patient::find($this->patientId)?->bpjs_card,
                    'prosthetic' => $this->prosthetic,
                    'prosthetic_since' => $this->prosthetic_since,
                    'address' => $this->address,
                ]
            );

            /** =========================
             * PATIENT PHOTOS
             * ========================= */
            if (!empty($this->patient_condition)) {

                // hapus foto lama (db + storage)
                PatientPhoto::where('patient_id', $patient->id)->each(function ($photo) {
                    if (Storage::disk('public')->exists($photo->url)) {
                        Storage::disk('public')->delete($photo->url);
                    }
                    $photo->delete();
                });

                // simpan foto baru
                foreach ($this->patient_condition as $file) {
                    $photoPath = $file->store('patient_photos', 'public');

                    PatientPhoto::create([
                        'patient_id' => $patient->id,
                        'url' => $photoPath,
                    ]);
                }
            }



            return redirect()->route(Auth::user()->role . '.dashboard')->with('success-alert', 'Profile updated successfully.');
        } catch (ValidationException $e) {
            $this->dispatch(
                'alert-error',
                collect($e->errors())->flatten()->first()
            );
        }
    }
    public function render()
    {
        return view('livewire.auth.profile', [
            'countries' => Country::orderBy('name')
                ->get(['id', 'name', 'code'])
                ->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'iso2' => strtolower($c->code)])
                ->values()
                ->toArray(),
        ]);
    }
}
