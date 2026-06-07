<?php

namespace App\Livewire\Admin;

use App\Models\Schedule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ScheduleList extends Component
{
    use WithPagination;

    public $search;

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        return view('livewire.admin.schedules.index', [
            'data' => Schedule::with('user')
                ->when($this->search, function ($q) {
                    $q->whereHas('user', fn($s) => $s->where('name', 'like', '%' . $this->search . '%'));
                })
                ->orderBy('date', 'desc')
                ->orderBy('time', 'desc')
                ->paginate(15),
        ]);
    }
}
