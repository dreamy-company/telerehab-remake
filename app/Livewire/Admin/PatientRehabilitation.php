<?php

namespace App\Livewire\Admin;

use App\Models\Patient;
use App\Models\RehabRoutine;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class PatientRehabilitation extends Component
{

    public $rehabilitations, $patientData;
    public function mount($id)
    {
        $this->rehabilitations = RehabRoutine::where('patient_id', $id)->get();
        $this->patientData = Patient::find($id);
    }
    public function render()
    {
        return view('livewire.admin.masterdata.patient.rehabilitation.index');
    }
}
