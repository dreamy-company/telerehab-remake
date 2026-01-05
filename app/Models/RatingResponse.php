<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RatingResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'routine_result_id',
        'doctor_id',
        'therapist_id',
        'review_therapist',
        'video_therapist',
        'video_doctor',
        'review_doctor',
        'isDeleted',
        'deleted_by',
        'deleted_at'
    ];

    public function rating()
    {
        return $this->belongsTo(RoutineRating::class, 'routine_rating_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function routineResult()
    {
        return $this->belongsTo(RoutineResult::class, 'routine_result_id');
    }
    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->deleted_by = Auth::user()->id;
            $model->save();
        });
    }
}
