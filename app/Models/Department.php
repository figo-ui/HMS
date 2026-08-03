<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'department_id',
        'name',
        'code',
        'description',
        'location',
        'phone',
        'email',
        'head_nurse_id',
        'status',
    ];

    public function nurses(): HasMany
    {
        return $this->hasMany(Nurse::class, 'department_id');
    }

    public function headNurse(): BelongsTo
    {
        return $this->belongsTo(Nurse::class, 'head_nurse_id');
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctors::class, 'department_id');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class, 'department_id');
    }
   
}
