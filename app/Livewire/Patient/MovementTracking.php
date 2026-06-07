<?php

namespace App\Livewire\Patient;

use App\Events\MovementSessionCompleted;
use App\Models\MovementSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class MovementTracking extends Component
{
    public int $sessionId;
    public int $repCount = 0;
    public float $currentAngle = 0;
    public string $status = 'ready'; // ready | tracking | done

    public function mount(int $sessionId): void
    {
        $session = MovementSession::with('exercise', 'patient')->findOrFail($sessionId);

        $patient = Auth::user()->patient;
        abort_if($patient === null || $session->patient_id !== $patient->id, 403);

        abort_if($session->status === 'completed', 403, 'This session has already been completed.');

        $this->sessionId = $sessionId;
        $this->repCount = $session->total_reps;
    }

    public function logMovement(array $data): void
    {
        $session = MovementSession::with('exercise')->findOrFail($this->sessionId);

        $patient = Auth::user()->patient;
        abort_if($patient === null || $session->patient_id !== $patient->id, 403);

        if ($session->status === 'completed') {
            return;
        }

        $angle = max(0, min(360, (float) ($data['angle'] ?? 0)));
        $rep = (int) ($data['rep'] ?? 0);
        $thresholds = $session->exercise->thresholds;
        $withinThreshold = $angle >= ($thresholds['min_angle'] ?? 0)
            && $angle <= ($thresholds['max_angle'] ?? 360);

        $session->logs()->create([
            'rep_number'       => $rep,
            'joint_angle'      => $angle,
            'within_threshold' => $withinThreshold,
            'landmark_snapshot' => null,
            'recorded_at'      => now(),
        ]);

        $this->repCount = $rep;
        $this->currentAngle = $angle;

        $session->increment('total_reps');
    }

    public function completeSession(): void
    {
        $session = MovementSession::with('exercise', 'logs')->findOrFail($this->sessionId);

        $patient = Auth::user()->patient;
        abort_if($patient === null || $session->patient_id !== $patient->id, 403);

        if ($session->status === 'completed') {
            $this->status = 'done';
            return;
        }

        $logs = $session->logs;
        $avgAngle = $logs->isNotEmpty() ? round($logs->avg('joint_angle'), 2) : null;
        $maxAngle = $logs->isNotEmpty() ? round($logs->max('joint_angle'), 2) : null;

        $session->update([
            'status'    => 'completed',
            'avg_angle' => $avgAngle,
            'max_angle' => $maxAngle,
            'total_reps' => $logs->count(),
        ]);

        $this->repCount = $session->total_reps;
        $this->status = 'done';

        broadcast(new MovementSessionCompleted($session));

        $this->dispatch('session-done');
    }

    public function render()
    {
        $session = MovementSession::with('exercise', 'logs')->findOrFail($this->sessionId);
        return view('livewire.patient.movement-tracking', compact('session'));
    }
}
