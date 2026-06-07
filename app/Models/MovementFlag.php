<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovementFlag extends Model
{
    protected $fillable = [
        'movement_session_id',
        'flagged_by',
        'flag_type',
        'note',
        'reviewed',
    ];

    protected $casts = [
        'reviewed' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(MovementSession::class, 'movement_session_id');
    }

    public function flaggedBy()
    {
        return $this->belongsTo(User::class, 'flagged_by');
    }
}
