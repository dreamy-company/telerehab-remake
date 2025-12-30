<?php

namespace App\Livewire\Admin;

use App\Models\Patient as ModelsPatient;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Patient extends Component
{
    protected $listeners = ['deleteConfirmed'];
    public $search, $idPatient, $patientData = null;

    public function detail($idPatient)
    {
        $this->patientData = ModelsPatient::find($idPatient);

    }

    public function delete($id)
    {
        $this->idPatient = $id;
        $patient = ModelsPatient::findOrFail($this->idPatient);
        if ($patient) {
            $this->dispatch('alert-delete', 'Are you sure you want to delete this Patient?');
        } else {
            $this->dispatch('alert-error', 'Patient not found.');
        }
    }

    public function deleteConfirmed()
    {
        $patient = ModelsPatient::findOrFail($this->idPatient);
        if ($patient) {
            $patient->isDeleted = true;
            $patient->deleted_by = Auth::user()->id;
            $patient->save();
            $patient->delete();
            $this->dispatch('delete-success', 'Patient deleted successfully.');
        } else {
            $this->dispatch('alert-error', 'Patient not found.');
        }
    }
    public function render()
    {
        return view('livewire.admin.masterdata.patient.index', [
            'data' => ModelsPatient::when($this->search, function ($query) {
                $query->where('medical_record_number', 'like', "%{$this->search}%")
                    ->orWhere('bpjs_number', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            })->whereHas('user', function($query) {
                $query->where('role', 'patient');
            })->get()
        ]);
    }
}
