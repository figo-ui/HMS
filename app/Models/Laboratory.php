<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laboratory extends Model
{
    protected $fillable = [
        'lab_id',
        'patient_id',
        'doctor_id',
        'service_request_id',
        'encounter_type',
        'encounter_id',
        'test_name',
        'test_type',
        'sample_type',
        'test_date',
        'result_date',
        'result_value',
        'result_data',
        'result_status',
        'normal_range',
        'cost',
        'notes',
        'report_path',
        'lab_report_path',
        'status',
    ];

    protected $casts = [
        'test_date' => 'date',
        'result_date' => 'date',
        'cost' => 'decimal:2',
        'result_data' => 'array',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patients::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctors::class, 'doctor_id');
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function paymentCleared(): bool
    {
        return $this->serviceRequest?->canProceedForService() ?? false;
    }
}
