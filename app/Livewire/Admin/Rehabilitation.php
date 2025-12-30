<?php

namespace App\Livewire\Admin;

use App\Models\Rehab;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Rehabilitation extends Component
{
    protected $listeners = ['deleteConfirmed'];
    public $search, $idToDelete;

    public function delete($id)
    {
        $this->idToDelete = $id;
        $rehabType = Rehab::findOrFail($this->idToDelete);
        if ($rehabType) {
            $this->dispatch('alert-delete', 'Are you sure you want to delete this Rehabilitation?');
        } else {
            $this->dispatch('alert-error', 'Rehabilitation not found.');
        }
    }

    public function deleteConfirmed()
    {
        $rehabType = Rehab::findOrFail($this->idToDelete);
        if ($rehabType) {
            $rehabType->isDeleted = true;
            $rehabType->deleted_by = Auth::user()->id;
            $rehabType->save();
            $rehabType->delete();
            $this->dispatch('delete-success', 'Rehabilitation deleted successfully.');
        } else {
            $this->dispatch('alert-error', 'Rehabilitation not found.');
        }
    }
    public function render()
    {
        return view('livewire.admin.masterdata.rehabilitation.index', [
            'data' => Rehab::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })->get()
        ]);
    }
}
