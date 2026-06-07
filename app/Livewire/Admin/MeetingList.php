<?php

namespace App\Livewire\Admin;

use App\Models\Meeting;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class MeetingList extends Component
{
    use WithPagination;

    protected $listeners = ['deleteConfirmed'];
    public $search, $filterStatus = '', $idToDelete;

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        $this->idToDelete = $id;
        $this->dispatch('alert-delete', 'Are you sure you want to delete this meeting?');
    }

    public function deleteConfirmed(): void
    {
        $meeting = Meeting::findOrFail($this->idToDelete);
        $meeting->isDeleted = true;
        $meeting->deleted_by = auth()->id();
        $meeting->save();
        $meeting->delete();
        $this->dispatch('delete-success', 'Meeting deleted successfully.');
    }

    public function render()
    {
        $query = Meeting::with(['doctor', 'patient.user'])
            ->when($this->search, function ($q) {
                $q->where(function ($inner) {
                    $inner->where('title', 'like', '%' . $this->search . '%')
                          ->orWhere('location', 'like', '%' . $this->search . '%')
                          ->orWhereHas('doctor', fn($s) => $s->where('name', 'like', '%' . $this->search . '%'))
                          ->orWhereHas('patient.user', fn($s) => $s->where('name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc');

        return view('livewire.admin.meetings.index', [
            'data' => $query->paginate(15),
        ]);
    }
}
