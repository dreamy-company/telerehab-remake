<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementExercise extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'target_joint', 'thresholds', 'description'];

    protected $casts = [
        'thresholds' => 'array',
    ];

    public function sessions()
    {
        return $this->hasMany(MovementSession::class);
    }
}
