<?php

namespace App\Livewire\Admin;

use App\Models\RehabType as ModelsRehabType;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class RehabType extends Component
{
    protected $listeners = ['deleteConfirmed'];
    public $search, $idToDelete;

    public function delete($id)
    {
        $this->idToDelete = $id;
        $rehabType = ModelsRehabType::findOrFail($this->idToDelete);
        if ($rehabType) {
            $this->dispatch('alert-delete', 'Are you sure you want to delete this Rehab Type?');
        } else {
            $this->dispatch('alert-error', 'Rehab Type not found.');
        }
    }

    public function deleteConfirmed()
    {
        $rehabType = ModelsRehabType::findOrFail($this->idToDelete);
        if ($rehabType) {
            $rehabType->isDeleted = true;
            $rehabType->deleted_by = Auth::user()->id;
            $rehabType->save();
            $rehabType->delete();
            $this->dispatch('delete-success', 'Rehab Type deleted successfully.');
        } else {
            $this->dispatch('alert-error', 'Rehab Type not found.');
        }
    }
    public function render()
    {
        return view('livewire.admin.masterdata.rehab-type.index', [
            'data' => ModelsRehabType::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })->orderBy('created_at', 'desc')->get()
        ]);
    }
}
