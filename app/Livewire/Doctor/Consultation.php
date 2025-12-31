<?php

namespace App\Livewire\Doctor;

use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Consultation extends Component
{

    public $search = '', $patientData = null, $date, $time, $category, $location;

    public function setConsultation($id)
    {
        $this->patientData = Meeting::with('patient.user')->find($id);
    }
    public function saveSchedule()
    {
        
        $this->validate([
            'date' => 'required|date',
            'time' => 'required',
            'category' => 'required|string',
            'location' => 'required|string',
        ]);

        $this->patientData->update([
            'doctor_id' => Auth::user()->id,
            'date' => $this->date,
            'time' => $this->time,
            'meeting_category' => $this->category,
            'location' => $this->location,
        ]);

       
        $this->patientData = null;
        return redirect()->route('doctor.consultation')->with('success-alert', 'Consultation schedule saved successfully.');
    }
    public function render()
    {

        return view('livewire.doctor.consultation.index', [
            'data' => Meeting::when($this->search, function ($query) {
                $query->whereHas('patient.user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
                ->where('status', 'pending')
                ->whereNull('doctor_id')
                ->get()
        ]);
    }
}
