<?php

namespace App\Livewire\Admin;

use App\Models\RehabType;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class RehabTypeForm extends Component
{

    public $name, $idRehabType;
    public function mount($id = null)
    {
        if ($id) {
            $this->idRehabType = $id;
            $rehabType = RehabType::findOrFail($this->idRehabType);
            $this->name = $rehabType->name;
        }
    }

    public function save()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255',
            ]);
            RehabType::updateOrCreate(
                ['id' => $this->idRehabType],
                [
                'name' => $this->name,
            ]);
            
            if($this->idRehabType){
                return redirect()->route('admin.rehab-type')->with('success-alert', 'Rehab Type updated successfully.');
            }
            return redirect()->route('admin.rehab-type')->with('success-alert', 'Rehab Type created successfully.');
        } catch (ValidationException $e) {
            $this->dispatch('error-alert',collect($e->errors())->flatten()->first());
        }
    }

    
    public function render()
    {
        return view('livewire.admin.masterdata.rehab-type.form');
    }
}
