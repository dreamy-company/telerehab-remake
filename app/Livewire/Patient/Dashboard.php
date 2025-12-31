<?php

namespace App\Livewire\Patient;

use App\Models\Meeting;
use App\Models\RehabRoutine;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    protected $listeners = ['requestConsultation'];

    public function requestConsultation()
    {
        $checkLatestConsultaion = Meeting::where('patient_id', Auth::user()->patient->id)
            ->where('status', 'pending')
            ->first();
        if ($checkLatestConsultaion) {
            $this->dispatch('alert-warning', 'There is a consultation schedule');
        } else {
            Meeting::create([
                'patient_id' => Auth::user()->patient->id,
                'status' => 'pending'
            ]);

            return redirect()->route('patient.dashboard')->with('success-alert', 'Consultation request has been sent successfully.');
        }
    }
    public function render()
    {
        $checkConsultation = Meeting::where('patient_id', Auth::user()->patient->id)
            ->where('status', 'pending')
            ->first();
        $checkRehabilitation = RehabRoutine::where('patient_id', Auth::user()->patient->id)
            ->where('status', 'process')
            ->latest()->first();
        return view('livewire.patient.dashboard', compact('checkConsultation', 'checkRehabilitation'));
    }
}
