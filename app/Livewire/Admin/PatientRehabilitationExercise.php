<?php

namespace App\Livewire\Admin;

use App\Models\MovementFlag;
use App\Models\MovementSession;
use App\Models\RatingResponse;
use App\Models\RehabRoutine;
use App\Models\RoutineResult;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class PatientRehabilitationExercise extends Component
{
    use WithFileUploads;

    public $patientId, $rehabRoutineId, $routineResults;
    public $movementSessions;

    public $activeResultId = null;
    public $video;
    public $review;
    public $activeFlagSessionId = null;
    public $flagType = 'wrong_form';
    public $flagNote = '';

    public function mount($id, $rehabRoutineId)
    {
        $this->patientId = $id;
        $this->rehabRoutineId = $rehabRoutineId;
        $this->routineResults = RoutineResult::where('rehab_routine_id', $this->rehabRoutineId)->orderBy('created_at', 'desc')->get();
        $this->movementSessions = MovementSession::with('exercise', 'logs', 'flags')
            ->where('rehab_routine_id', $this->rehabRoutineId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    public function openModal($resultId)
    {
        $this->reset(['video', 'review']);
        $this->activeResultId = $resultId;
    }

    public function submitFeedback()
    {

        $resultId = $this->activeResultId;

        $checkRatingResponse = RatingResponse::where('routine_result_id', $resultId)->first();

        $user = Auth::user();

        if ($checkRatingResponse) {
            $updateData = [];
            if ($user->role === 'doctor') {
                $updateData['video_doctor'] = $this->video?->store('feedback_videos', 'public') ?? $checkRatingResponse->video_doctor;
                $updateData['review_doctor'] = $this->review;
                $updateData['doctor_id'] = $user->id;
            } elseif ($user->role === 'therapist') {
                $updateData['video_therapist'] = $this->video?->store('feedback_videos', 'public') ?? $checkRatingResponse->video_therapist;
                $updateData['review_therapist'] = $this->review;
                $updateData['therapist_id'] = $user->id;
            }
            $checkRatingResponse->update($updateData);
            $userRole = $user->role;
            return redirect()->route($userRole . '.patient.rehabilitation.exercise', ['id' => $this->patientId, 'rehabRoutineId' => $this->rehabRoutineId])->with('success-alert', 'Feedback submitted successfully.');
        } else {
            $data = ['routine_result_id' => $resultId];
            if ($user->role === 'doctor') {
                $data['video_doctor'] = $this->video?->store('feedback_videos', 'public');
                $data['review_doctor'] = $this->review;
                $data['doctor_id'] = $user->id;
            } elseif ($user->role === 'therapist') {
                $data['therapist_id'] = $user->id;
                $data['video_therapist'] = $this->video?->store('feedback_videos', 'public');
                $data['review_therapist'] = $this->review;
            }
            RatingResponse::create($data);

            $userRole = $user->role;
            return redirect()->route($userRole . '.patient.rehabilitation.exercise', ['id' => $this->patientId, 'rehabRoutineId' => $this->rehabRoutineId])->with('success-alert', 'Feedback submitted successfully.');
        }
    }

    public function openFlagModal(int $sessionId): void
    {
        $user = Auth::user();
        abort_if(!in_array($user->role, ['doctor', 'therapist', 'admin']), 403);

        $this->activeFlagSessionId = $sessionId;
        $this->flagType = 'wrong_form';
        $this->flagNote = '';
    }

    public function submitFlag(): void
    {
        $user = Auth::user();
        abort_if(!in_array($user->role, ['doctor', 'therapist', 'admin']), 403);

        $this->validate([
            'flagType' => 'required|in:incomplete_range,wrong_form,missed_reps',
            'flagNote' => 'nullable|string|max:500',
        ]);

        $session = MovementSession::findOrFail($this->activeFlagSessionId);

        MovementFlag::create([
            'movement_session_id' => $session->id,
            'flagged_by'          => $user->id,
            'flag_type'           => $this->flagType,
            'note'                => $this->flagNote ?: null,
        ]);

        $session->update(['status' => 'flagged']);

        $this->movementSessions = MovementSession::with('exercise', 'logs', 'flags')
            ->where('rehab_routine_id', $this->rehabRoutineId)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->activeFlagSessionId = null;
        $this->dispatch('alert-success', 'Session flagged successfully.');
    }

    public function render()
    {
        return view('livewire.admin.masterdata.patient.rehabilitation.exercise');
    }
}
