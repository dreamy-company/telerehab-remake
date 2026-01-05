<?php

namespace App\Livewire\Therapist;

use App\Models\Patient;
use App\Models\Rehab;
use App\Models\RehabType;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.therapist.dashboard',[
            'totalPatient' => Patient::all()->count(),
            'totalRehabilitationPhases' => RehabType::all()->count(),
            'totalRehabilitations' => Rehab::all()->count(),
        ]);
    }
}
