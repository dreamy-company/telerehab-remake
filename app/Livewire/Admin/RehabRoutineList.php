<?php

namespace App\Livewire\Admin;

use App\Models\RehabRoutine;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class RehabRoutineList extends Component
{
    use WithPagination;

    protected $listeners = ['deleteConfirmed'];
    public $search, $filterStatus = '', $idToDelete;

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        $this->idToDelete = $id;
        $this->dispatch('alert-delete', 'Are you sure you want to delete this rehab routine?');
    }

    public function deleteConfirmed(): void
    {
        $routine = RehabRoutine::findOrFail($this->idToDelete);
        $routine->isDeleted = true;
        $routine->deleted_by = Auth::id();
        $routine->save();
        $routine->delete();
        $this->dispatch('delete-success', 'Rehab routine deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.rehab-routines.index', [
            'data' => RehabRoutine::with(['patient.user', 'doctor', 'rehabilitation'])
                ->when($this->search, function ($q) {
                    $q->where(function ($inner) {
                        $inner->where('goal', 'like', '%' . $this->search . '%')
                              ->orWhereHas('patient.user', fn($s) => $s->where('name', 'like', '%' . $this->search . '%'))
                              ->orWhereHas('doctor', fn($s) => $s->where('name', 'like', '%' . $this->search . '%'))
                              ->orWhereHas('rehabilitation', fn($s) => $s->where('name', 'like', '%' . $this->search . '%'));
                    });
                })
                ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
                ->orderBy('created_at', 'desc')
                ->paginate(15),
        ]);
    }
}
