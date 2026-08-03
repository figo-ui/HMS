<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctors extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'full_name',
        'specialization',
        'license_no',
        'phone',
        'email',
        'department_id',
        'availability_schedule',
        'consultation_fee',
        'status',
    ];

    protected $casts = [
        'availability_schedule' => 'array',
        'consultation_fee' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function opdEncounters(): HasMany
    {
        return $this->hasMany(OPD::class, 'doctor_id');
    }

    public function ipdEncounters(): HasMany
    {
        return $this->hasMany(IPD::class, 'doctor_id');
    }
}
