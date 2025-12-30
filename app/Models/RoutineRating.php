<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RoutineRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'rehab_routine_id', 'rate', 'review',
        'isDeleted', 'deleted_by', 'deleted_at'
    ];

    public function rehabRoutine()
    {
        return $this->belongsTo(RehabRoutine::class);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function response()
    {
        return $this->hasOne(RatingResponse::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->deleted_by = Auth::user()->id;
            $model->save();

            if ($model->response) {
                $model->response->deleted_by = Auth::user()->id;
                $model->response->save();
                $model->response->delete();
            }
        });
    }
}
