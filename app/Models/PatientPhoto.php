<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PatientPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'url'];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    // public static function boot()
    // {
    //     parent::boot();

    //     static::deleting(function ($model) {
    //         $model->deleted_by = Auth::user()->id;
    //         $model->save();
    //     });
    // }
}
