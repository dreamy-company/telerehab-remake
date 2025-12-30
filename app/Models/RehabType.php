<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class RehabType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'isDeleted', 'deleted_by', 'deleted_at'];

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function rehabs()
    {
        return $this->hasMany(Rehab::class, 'rehabilitation_type_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->deleted_by = Auth::user()->id;
            $model->save();
            
            $model->rehabs()->each(function ($rehab) {
                $rehab->deleted_by = Auth::user()->id;
                $rehab->save();
                $rehab->delete();
            });
        });
    }
}
