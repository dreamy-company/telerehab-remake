<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Rehab extends Model
{
    use HasFactory;

    protected $fillable = ['rehabilitation_type_id', 'name', 'description', 'video_url', 'isDeleted', 'deleted_by', 'deleted_at'];

    public function rehabType()
    {
        return $this->belongsTo(RehabType::class, 'rehabilitation_type_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function rehabRoutines()
    {
        return $this->hasMany(RehabRoutine::class, 'rehabilitation_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->deleted_by = Auth::user()->id;
            $model->save();

            $model->rehabRoutines()->each(function ($routine) {
                $routine->deleted_by = Auth::user()->id;
                $routine->save();
                $routine->delete();
            });
        });
    }
}
