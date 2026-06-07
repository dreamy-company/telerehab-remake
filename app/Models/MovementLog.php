<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovementLog extends Model
{
    protected $fillable = [
        'movement_session_id',
        'rep_number',
        'joint_angle',
        'within_threshold',
        'landmark_snapshot',
        'recorded_at',
    ];

    protected $casts = [
        'within_threshold' => 'boolean',
        'landmark_snapshot' => 'array',
        'recorded_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(MovementSession::class, 'movement_session_id');
    }
}
