<?php

namespace App\Events;

use App\Models\MovementSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MovementSessionCompleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $sessionId;
    public int $patientId;
    public int $totalReps;
    public ?float $avgAngle;
    public ?float $maxAngle;
    public ?int $rehabRoutineId;

    public function __construct(MovementSession $session)
    {
        $this->sessionId = $session->id;
        $this->patientId = $session->patient_id;
        $this->totalReps = $session->total_reps;
        $this->avgAngle = $session->avg_angle;
        $this->maxAngle = $session->max_angle;
        $this->rehabRoutineId = $session->rehab_routine_id;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('movement-tracking');
    }

    public function broadcastAs(): string
    {
        return 'session-completed';
    }
}
