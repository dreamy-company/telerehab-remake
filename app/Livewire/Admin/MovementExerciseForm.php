<?php

namespace App\Livewire\Admin;

use App\Models\MovementExercise;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class MovementExerciseForm extends Component
{
    public $name, $targetJoint, $minAngle, $maxAngle, $targetReps, $description, $idExercise;

    public function mount($id = null)
    {
        if ($id) {
            $this->idExercise = $id;
            $exercise = MovementExercise::findOrFail($this->idExercise);
            $this->name = $exercise->name;
            $this->targetJoint = $exercise->target_joint;
            $this->minAngle = $exercise->thresholds['min_angle'] ?? null;
            $this->maxAngle = $exercise->thresholds['max_angle'] ?? null;
            $this->targetReps = $exercise->thresholds['target_reps'] ?? null;
            $this->description = $exercise->description;
        }
    }

    public function save()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'targetJoint' => 'required|string|in:right_elbow,left_elbow,right_shoulder,left_shoulder,right_knee,left_knee,right_hip,left_hip',
                'minAngle' => 'required|integer',
                'maxAngle' => 'required|integer',
                'targetReps' => 'required|integer',
                'description' => 'nullable|string',
            ]);

            MovementExercise::updateOrCreate(
                ['id' => $this->idExercise],
                [
                    'name' => $this->name,
                    'target_joint' => $this->targetJoint,
                    'thresholds' => [
                        'min_angle' => (int) $this->minAngle,
                        'max_angle' => (int) $this->maxAngle,
                        'target_reps' => (int) $this->targetReps,
                    ],
                    'description' => $this->description,
                ]
            );

            if ($this->idExercise) {
                return redirect()->route('admin.movement-exercise')->with('success-alert', 'Movement Exercise updated successfully.');
            }
            return redirect()->route('admin.movement-exercise')->with('success-alert', 'Movement Exercise created successfully.');
        } catch (ValidationException $e) {
            $this->dispatch('error-alert', collect($e->errors())->flatten()->first());
        }
    }

    public function render()
    {
        return view('livewire.admin.masterdata.movement-exercise.form');
    }
}
