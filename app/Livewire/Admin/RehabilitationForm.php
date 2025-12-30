<?php

namespace App\Livewire\Admin;

use App\Models\Rehab;
use App\Models\RehabType as rehabilitationPhase;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class RehabilitationForm extends Component
{

    public $name, $rehabilitationPhase, $description, $videoUrl, $idRehabilitation;
    public function mount($id = null)
    {
        if ($id) {
            $this->idRehabilitation = $id;
            $rehabilitation = Rehab::findOrFail($this->idRehabilitation);
            $this->name = $rehabilitation->name;
            $this->rehabilitationPhase = $rehabilitation->rehabilitation_type_id;
            $this->description = $rehabilitation->description;
            $this->videoUrl = $rehabilitation->video_url;

        }
    }

    public function save()
    {
        
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'rehabilitationPhase' => 'required|integer|exists:rehab_types,id',
                'description' => 'required|string',
                'videoUrl' => 'required|string',
            ]);
            Rehab::updateOrCreate(
                ['id' => $this->idRehabilitation],
                [
                'name' => $this->name,
                'rehabilitation_type_id' => $this->rehabilitationPhase,
                'description' => $this->description,
                'video_url' => $this->videoUrl,
            ]);
            
            if($this->idRehabilitation){
                return redirect()->route('admin.rehabilitation')->with('success-alert', 'Rehabilitation updated successfully.');
            }
            return redirect()->route('admin.rehabilitation')->with('success-alert', 'Rehabilitation created successfully.');
        } catch (ValidationException $e) {
            $this->dispatch('error-alert',collect($e->errors())->flatten()->first());
        }
    }

    
    public function render()
    {
        return view('livewire.admin.masterdata.rehabilitation.form',[
            'rehabType' => rehabilitationPhase::all(),
        ]);
    }
}
