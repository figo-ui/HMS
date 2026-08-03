<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends Model
{
    protected $fillable = [
        'prescription_id',
        'patient_id',
        'doctor_id',
        'encounter_type',
        'encounter_id',
        'prescribed_date',
        'diagnosis',
        'medications',
        'instructions',
        'notes',
        'status',
    ];

    protected $casts = [
        'prescribed_date' => 'date',
        'medications' => 'array',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patients::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctors::class, 'doctor_id');
    }

    public function pharmacySales(): HasMany
    {
        return $this->hasMany(Pharmacy::class, 'prescription_sale_id', 'prescription_id');
    }

    public function saleInvoices(): HasMany
    {
        return $this->hasMany(PharmacySale::class, 'prescription_id', 'prescription_id');
    }
}
