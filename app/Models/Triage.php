<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Triage extends Model
{
    protected $fillable = [
        'triage_id',
        'patient_id',
        'nurse_id',
        'encounter_id',
        'priority',
        'chief_complaint',
        'temperature',
        'blood_pressure',
        'pulse_rate',
        'respiratory_rate',
        'oxygen_saturation',
        'notes',
        'status',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patients::class, 'patient_id');
    }

    public function nurse(): BelongsTo
    {
        return $this->belongsTo(Nurse::class, 'nurse_id');
    }

    public function opdEncounter(): BelongsTo
    {
        return $this->belongsTo(OPD::class, 'encounter_id', 'encounter_id');
    }

    public function ipdEncounter(): BelongsTo
    {
        return $this->belongsTo(IPD::class, 'encounter_id', 'encounter_id');
    }
}
