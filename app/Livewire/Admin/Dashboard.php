<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard',[
            'totalPatients' => \App\Models\Patient::count(),
            'totalRehabTypes' => \App\Models\RehabType::count(),
            'totalRehabilitations' => \App\Models\Rehab::count(),
        ]);
    }
}
