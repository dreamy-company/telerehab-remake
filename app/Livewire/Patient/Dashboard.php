<?php

namespace App\Livewire\Patient;

use App\Events\UpdateEvent;
use App\Models\Meeting;
use App\Models\RehabRoutine;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\On;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    protected $listeners = ['requestConsultation'];

    #[On('echo:update-channel,.update-event')]
    public function handleUpdate($payload)
    {
        $message = $payload['message'];

        if ($message === 'Update Data') {
            $this->render();
        } elseif ($message === 'New Schedule') {
            $this->dispatch('toaster-info', 'There is a new consultation schedule.');
        } elseif( $message === 'Schedule Updated') {
            $this->dispatch('toaster-info', 'A consultation schedule has been updated.');
        }
    }

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

            broadcast(new UpdateEvent('Request Consultation'))->toOthers();
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
        $consultationDatas = Meeting::where('patient_id', Auth::user()->patient->id)
            ->latest()->get();
        return view('livewire.patient.dashboard', compact('checkConsultation', 'checkRehabilitation', 'consultationDatas'));
    }
}
