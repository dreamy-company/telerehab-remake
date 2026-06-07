<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'movement_exercise_id',
        'rehab_routine_id',
        'session_date',
        'total_reps',
        'avg_angle',
        'max_angle',
        'status',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function exercise()
    {
        return $this->belongsTo(MovementExercise::class, 'movement_exercise_id');
    }

    public function rehabRoutine()
    {
        return $this->belongsTo(RehabRoutine::class);
    }

    public function logs()
    {
        return $this->hasMany(MovementLog::class);
    }

    public function flags()
    {
        return $this->hasMany(MovementFlag::class);
    }

    public function getCompletionRateAttribute(): float
    {
        $target = $this->exercise?->thresholds['target_reps'] ?? 1;
        if ($target <= 0) {
            return 0.0;
        }
        return round(min($this->total_reps / $target, 1.0) * 100, 1);
    }
}
