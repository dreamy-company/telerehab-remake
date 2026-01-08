<?php

namespace App\Livewire\Admin;

use App\Models\Patient;
use App\Models\PatientPhoto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class PatientForm extends Component
{
    use WithFileUploads;
    public $patientId, $userId, $name, $email, $password, $country, $telephone, $medical_record_number, $bpjs_number, $bpjs_card, $patient_condition = [], $address, $prosthetic, $prosthetic_since, $old_patient_condition, $old_bpjs_card;



    public function mount($id = null)
    {
        if ($id) {
            $this->patientId = $id;
            $patientData = Patient::find($id);
            $this->userId = $patientData->user->id;
            $this->name = $patientData->user->name;
            $this->email = $patientData->user->email;
            $this->country = $patientData->user->country;
            $this->telephone = $patientData->user->telephone;
            $this->medical_record_number = $patientData->medical_record_number;
            $this->bpjs_number = $patientData->bpjs_number;
            $this->address = $patientData->address;
            $this->prosthetic = $patientData->prosthetic;
            $this->prosthetic_since = $patientData->prosthetic_since;
            $this->old_bpjs_card = $patientData->bpjs_card;
            $this->old_patient_condition = $patientData->photos;
        }
    }

    public function save()
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
                'country' => 'required',

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
                    'country' => $this->country,
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


            return redirect()
                ->route('admin.patient')
                ->with('success-alert', 'Patient saved successfully.');
        } catch (ValidationException $e) {
            $this->dispatch(
                'alert-error',
                collect($e->errors())->flatten()->first()
            );
        }
    }
    public function render()
    {
        return view('livewire.admin.masterdata.patient.form');
    }
}
