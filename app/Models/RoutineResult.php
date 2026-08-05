<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutineResult extends Model
{
    protected $guarded = ['id'];
    public function routine()
    {
        return $this->belongsTo(RehabRoutine::class, 'rehab_routine_id');
    }
    // patient_id merujuk ke tabel `patients`, bukan `users`.
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function rehabRoutine()
    {
        return $this->belongsTo(RehabRoutine::class, 'rehab_routine_id');
    }
    public function ratingResponse()
    {
        return $this->hasOne(RatingResponse::class, 'routine_result_id', 'id');
    }

}
