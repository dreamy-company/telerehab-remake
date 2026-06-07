<?php

namespace App\Livewire\Admin;

use App\Models\MovementExercise as ModelsMovementExercise;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class MovementExercise extends Component
{
    protected $listeners = ['deleteConfirmed'];
    public $search, $idToDelete;

    public function delete($id)
    {
        $this->idToDelete = $id;
        $exercise = ModelsMovementExercise::findOrFail($this->idToDelete);
        if ($exercise) {
            $this->dispatch('alert-delete', 'Are you sure you want to delete this Movement Exercise?');
        } else {
            $this->dispatch('alert-error', 'Movement Exercise not found.');
        }
    }

    public function deleteConfirmed()
    {
        $exercise = ModelsMovementExercise::findOrFail($this->idToDelete);
        if ($exercise) {
            $exercise->delete();
            $this->dispatch('delete-success', 'Movement Exercise deleted successfully.');
        } else {
            $this->dispatch('alert-error', 'Movement Exercise not found.');
        }
    }

    public function render()
    {
        return view('livewire.admin.masterdata.movement-exercise.index', [
            'data' => ModelsMovementExercise::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('target_joint', 'like', '%' . $this->search . '%');
            })->orderBy('created_at', 'desc')->get()
        ]);
    }
}
