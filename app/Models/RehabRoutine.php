<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RehabRoutine extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'status',
        'rehabilitation_id',
        'goal',
        'target',
        'isDeleted',
        'deleted_by',
        'deleted_at'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function rehabilitation()
    {
        return $this->belongsTo(Rehab::class, 'rehabilitation_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function ratings()
    {
        return $this->hasMany(RoutineRating::class);
    }

    public function routineResults()
    {
        return $this->hasMany(RoutineResult::class, 'rehab_routine_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->deleted_by = Auth::user()->id;
            $model->save();

            $model->ratings()->each(function ($rating) {
                $rating->deleted_by = Auth::user()->id;
                $rating->save();
                $rating->delete();
            });
        });
    }
}
