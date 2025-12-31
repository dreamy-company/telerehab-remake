<?php

namespace App\Livewire\Doctor;

use App\Models\Meeting;
use App\Models\Rehab;
use App\Models\RehabRoutine;
use App\Models\RehabType;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class MeetingScheduleConsultation extends Component
{
    protected $listeners = ['select2-rehab-initialized' => 'select2RehabInitialized'];
    public $meetingId, $patientData = null, $phase = null, $rehabilitation = null, $targetDate, $goal, $diagnosis, $medicine, $rehabilitations = [];

    public function mount($id)
    {
        if ($id) {
            $this->meetingId = $id;

            $this->patientData = Meeting::with('patient.user', 'patient.photos')->find($id);
        }
    }

    public function select2RehabInitialized()
    {
        $this->rehabilitations = Rehab::where('rehabilitation_type_id', $this->phase)->get();
    }

    public function updatedPhase()
    {
        $this->dispatch('select2-rehab');
    }

    public function saveSchedule()
    {
        try {
            $this->validate([
                'phase' => 'required|string',
                'rehabilitation' => 'required|string',
                'targetDate' => 'required|date',
                'goal' => 'required|string',
                'diagnosis' => 'required|string',
                'medicine' => 'required|string',
            ]);

            Meeting::where('id', $this->meetingId)->update(['status' => 'done']);
            $rehabilitationRoutine = RehabRoutine::create([
                'patient_id' => $this->patientData->patient_id,
                'doctor_id' => $this->patientData->doctor_id,
                'rehabilitation_id' => $this->rehabilitation,
                'target' => $this->targetDate,
                'goal' => $this->goal,
                'status' => 'process',
                'diagnosis' => $this->diagnosis,
                'medicine' => $this->medicine,
            ]);
            if ($rehabilitationRoutine) {
                return redirect()->route('doctor.meeting-schedule')->with('success-alert', 'Consultation saved successfully.');
            }
        } catch (ValidationException $e) {
            $this->dispatch('alert-error', collect($e->errors())->flatten()->first());
        }
    }
    public function render()
    {
        return view('livewire.doctor.meeting-schedule.consultation', [
            'phases' => RehabType::all(),
            'rehabilitations' => $this->rehabilitations = Rehab::where('rehabilitation_type_id', $this->phase)->get(),
        ]);
    }
}
