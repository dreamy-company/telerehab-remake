<?php

namespace App\Livewire\Doctor;

use App\Models\Meeting;
use App\Models\Rehab;
use App\Models\RehabType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class MeetingSchedule extends Component
{

    public $search = '';


    public function saveSchedule()
    {

        $this->validate([
            'phase' => 'required|string',
            'rehabilitation' => 'required|string',
            'targetDate' => 'required|date',
            'goal' => 'required|string',
            'diagnosis' => 'required|string',
            'medicine' => 'required|string',
        ]);

        
        return redirect()->route('doctor.consultation')->with('success-alert', 'Consultation schedule saved successfully.');
    }
    public function render()
    {

        return view('livewire.doctor.meeting-schedule.index', [
            'data' => Meeting::when($this->search, function ($query) {
                $query->whereHas('patient.user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
                ->with('patient')
                ->where('status', 'pending')
                ->where('doctor_id', Auth::user()->id)
                ->where('status', 'pending')
                ->get(),

        ]);
    }
}
