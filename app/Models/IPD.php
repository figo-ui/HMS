<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IPD extends Model
{
    protected $fillable = [
        'encounter_id',
        'patient_id',
        'type',
        'doctor_id',
        'department',
        'diagnosis',
        'admission_date',
        'discharge_date',
        'bed_id',
        'treatment_plan',
        'prescription_id',
        'status',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'discharge_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patients::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctors::class, 'doctor_id');
    }


    public function bed(): BelongsTo
    {
        return $this->belongsTo(Beds::class, 'bed_id', 'bed_id');
    }

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class, 'prescription_id', 'prescription_id');
    }

    public function billingPayments(): HasMany
    {
        return $this->hasMany(BillingPayment::class, 'encounter_id', 'encounter_id');
    }

    public function triage(): HasOne
    {
        return $this->hasOne(Triage::class, 'encounter_id', 'encounter_id');
    }
}
