<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Radiology extends Model
{
    protected $fillable = [
        'radiology_id',
        'patient_id',
        'doctor_id',
        'service_request_id',
        'encounter_type',
        'encounter_id',
        'exam_name',
        'modality',
        'exam_date',
        'result_summary',
        'findings',
        'conclusion',
        'report_image',
        'completed_at',
        'result_status',
        'status',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'completed_at' => 'datetime',
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
