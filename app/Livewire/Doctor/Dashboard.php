<?php

namespace App\Livewire\Doctor;

use App\Models\Meeting;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.doctor.dashboard',[
            'totalPatient' => Patient::whereHas('meetings', function($q){
                $q->where('doctor_id', Auth::user()->id);
            })->count(),
            'totalConsultationRequest' => Meeting::where('status', 'pending')->count(),
            'totalRehabilitationPhases' => Patient::whereHas('rehabRoutines', function($q){
                $q->where('doctor_id', Auth::user()->id)->where('status', 'process');
            })->count(),
        ]);
    }
}
