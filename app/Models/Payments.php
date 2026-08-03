<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Payments extends Model
{
    use HasFactory;

    /**
     * Explicitly defining the table name to ensure it matches your database.
     * @var string
     */
    protected $table = 'payments';
    protected $fillable = [
        'patient_id',
        'service_request_id',
        'amount',
        'payment_method',
        'payment_status',
        'payment_date',
    ];
   
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patients::class, 'patient_id');
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }
}