<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Insurance extends Model
{
    protected $fillable = [
        'policy_id',
        'patient_id',
        'provider_name',
        'policy_no',
        'coverage_limit',
        'co_pay',
        'valid_from',
        'valid_to',
        'claim_id',
        'claim_status',
        'approved_amount',
    ];

    protected $casts = [
        'coverage_limit' => 'decimal:2',
        'co_pay' => 'decimal:2',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'approved_amount' => 'decimal:2',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patients::class, 'patient_id');
    }
}
