<?php

namespace App\Livewire\Admin;

use App\Models\Country as ModelsCountry;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Country extends Component
{
    use WithPagination;

    protected $listeners = ['deleteConfirmed'];
    public $search, $idToDelete;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $this->idToDelete = $id;
        $country = ModelsCountry::findOrFail($id);
        if ($country) {
            $this->dispatch('alert-delete', 'Are you sure you want to delete this country?');
        }
    }

    public function deleteConfirmed(): void
    {
        $country = ModelsCountry::findOrFail($this->idToDelete);
        $country->delete();
        $this->dispatch('delete-success', 'Country deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.masterdata.country.index', [
            'data' => ModelsCountry::when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            })->orderBy('name')->paginate(20),
        ]);
    }
}
