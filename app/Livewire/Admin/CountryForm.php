<?php

namespace App\Livewire\Admin;

use App\Models\Country as ModelsCountry;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class CountryForm extends Component
{
    public $idCountry, $name, $code;

    public function mount($id = null): void
    {
        if ($id) {
            $this->idCountry = $id;
            $country = ModelsCountry::findOrFail($id);
            $this->name = $country->name;
            $this->code = $country->code;
        }
    }

    public function save(): void
    {
        try {
            $this->validate([
                'name' => 'required|string|max:100',
                'code' => 'required|string|size:2|alpha|unique:countries,code,' . ($this->idCountry ?? 'NULL'),
            ]);

            $this->code = strtoupper(trim($this->code));

            ModelsCountry::updateOrCreate(
                ['id' => $this->idCountry],
                ['name' => trim($this->name), 'code' => $this->code]
            );

            $message = $this->idCountry ? 'Country updated successfully.' : 'Country created successfully.';
            $this->redirect(route('admin.country'), navigate: true);
            session()->flash('success-alert', $message);
        } catch (ValidationException $e) {
            $this->dispatch('alert-error', collect($e->errors())->flatten()->first());
        }
    }

    public function render()
    {
        return view('livewire.admin.masterdata.country.form');
    }
}
