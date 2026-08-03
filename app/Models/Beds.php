<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Beds extends Model
{
    protected $fillable = [
        'ward_id',
        'ward_name',
        'bed_id',
        'bed_no',
        'bed_type',
        'charge_per_day',
        'occupancy_status',
        'current_patient_id',
        'assigned_nurse',
        'last_cleaned_at',
    ];

    protected $casts = [
        'charge_per_day' => 'decimal:2',
        'last_cleaned_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patients::class, 'current_patient_id');
    }

    public function nurse(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'assigned_nurse', 'staff_id');
    }

    public function opdAdmissions(): HasMany
    {
        return $this->hasMany(OPD::class, 'bed_id', 'bed_id');
    }

    public function ipdAdmissions(): HasMany
    {
        return $this->hasMany(IPD::class, 'bed_id', 'bed_id');
    }
}
