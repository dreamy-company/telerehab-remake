<?php

namespace App\Livewire\Patient;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Rehabilitation extends Component
{
    public function render()
    {
        return view('livewire.patient.rehabilitation.index');
    }
}
