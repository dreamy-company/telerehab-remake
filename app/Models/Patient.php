<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'medical_record_number', 
        'bpjs_number',
        'bpjs_card',
        'prosthetic',
        'prosthetic_since',
        'address',
        'isDeleted',
        'deleted_by',
        'deleted_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($patient) {

            if (Auth::check()) {
                $patient->deleted_by = Auth::id();
                $patient->save();
            }

            $patient->photos()->delete();
            $patient->scores()->delete();
            $patient->rehabRoutines()->delete();
        });

        static::restoring(function ($patient) {
            $patient->photos()->withTrashed()->restore();
            $patient->scores()->withTrashed()->restore();
            $patient->rehabRoutines()->withTrashed()->restore();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function photos()
    {
        return $this->hasMany(PatientPhoto::class);
    }

    public function scores()
    {
        return $this->hasMany(PatientScore::class);
    }

    public function rehabRoutines()
    {
        return $this->hasMany(RehabRoutine::class);
    }
}
