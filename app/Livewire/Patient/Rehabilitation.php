<?php

namespace App\Livewire\Patient;

use App\Models\RehabRoutine;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Rehabilitation extends Component
{
    public $search = '';
    public function render()
    {
        return view('livewire.patient.rehabilitation.index',[
            'data' => RehabRoutine::where('patient_id', Auth::user()->patient->id)
                ->orderBy('created_at', 'desc')
                ->get(),
        ]);
    }
}
