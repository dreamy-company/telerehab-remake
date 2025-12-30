<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PatientScore extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['patient_id', 'score', 'isDeleted', 'deleted_by', 'deleted_at'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
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
