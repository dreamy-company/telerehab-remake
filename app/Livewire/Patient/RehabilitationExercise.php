<?php

namespace App\Livewire\Patient;

use App\Models\MovementExercise;
use App\Models\MovementSession;
use App\Models\RehabRoutine;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class RehabilitationExercise extends Component
{
    use WithFileUploads;

    public $rehabRoutineId, $rehabData, $results, $video, $feedback, $rehabOverdue = false;
    public $movementExercises, $selectedMovementExerciseId;

    public function mount($id)
    {
        $this->rehabRoutineId = $id;
        $this->rehabData = RehabRoutine::with('rehabilitation')->findOrFail($this->rehabRoutineId);
        $this->results = $this->rehabData->routineResults()->orderBy('created_at', 'desc')->get();
        $this->movementExercises = MovementExercise::all();
    }

    public function uploadVideo()
    {
        $this->validate([
            'video' => 'required|mimes:mp4,mov,avi|max:51200',
            'feedback' => 'required|string',
        ]);

        $path = $this->video->store('rehab_videos', 'public');

        $this->rehabData->routineResults()->create([
            'video_url' => $path,
            'feedback' => $this->feedback,
            'patient_id' => Auth::user()->patient->id,
            'date' => now(),
        ]);

        $this->video = null;
        $this->feedback = null;
        $this->results = $this->rehabData->routineResults()->get();

        return redirect()->route('patient.rehabilitation.exercise', ['id' => $this->rehabRoutineId])->with('success-alert', 'Video uploaded successfully.');
    }
    public function startTracking(): void
    {
        $this->validate(['selectedMovementExerciseId' => 'required|exists:movement_exercises,id']);

        $patient = Auth::user()->patient;

        $session = MovementSession::create([
            'patient_id'           => $patient->id,
            'movement_exercise_id' => $this->selectedMovementExerciseId,
            'rehab_routine_id'     => $this->rehabRoutineId,
            'session_date'         => now()->toDateString(),
            'status'               => 'in_progress',
        ]);

        $this->redirect(route('patient.movement.track', $session->id));
    }

    public function render()
    {
        return view('livewire.patient.rehabilitation.exercise.index');
    }
}
