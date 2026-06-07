<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country_id',
        'telephone',
        'role',
        'isDeleted',
        'deleted_by',
        'deleted_at'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class, 'user_id');
    }
    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id');
    }
    
    public function doctorPatients()
    {
        return $this->hasMany(Patient::class, 'doctor_id');
    }

    
    public function deletedPatients()
    {
        return $this->hasMany(Patient::class, 'deleted_by');
    }

    public function deletedPatientScores()
    {
        return $this->hasMany(PatientScore::class, 'deleted_by');
    }

    public function deletedRehabTypes()
    {
        return $this->hasMany(RehabType::class, 'deleted_by');
    }

    public function deletedRehabs()
    {
        return $this->hasMany(Rehab::class, 'deleted_by');
    }

    public function deletedRehabRoutines()
    {
        return $this->hasMany(RehabRoutine::class, 'deleted_by');
    }

    public function deletedRoutineRatings()
    {
        return $this->hasMany(RoutineRating::class, 'deleted_by');
    }

    public function doctorRatingResponses()
    {
        return $this->hasMany(RatingResponse::class, 'doctor_id');
    }

    public function therapistRatingResponses()
    {
        return $this->hasMany(RatingResponse::class, 'therapist_id');
    }
    public function deletedRatingResponses()
    {
        return $this->hasMany(RatingResponse::class, 'deleted_by');
    }

    public function deletedMeetings()
    {
        return $this->hasMany(Meeting::class, 'deleted_by');
    }

    
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            $user->deleted_by = Auth::user()->id;
            $user->save();
        });
    }
    
}
