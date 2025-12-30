<?php

namespace App\Livewire\Doctor;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.doctor.dashboard');
    }
}
