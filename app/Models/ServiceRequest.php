<?php

namespace App\Models;
use App\Models\Payments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Events\PaymentRequested;
use App\Events\PaymentVerified;
use App\Support\ServiceRequestNotifier;

class ServiceRequest extends Model
{
    protected $table = 'service_requests';
    
    protected $fillable = [
        'request_number', 'patient_id', 'encounter_type', 'encounter_id', 'service_id',
        'requested_by', 'verified_by', 'collected_by',
        'payment_status', 'fulfillment_status',
        'total_amount', 'patient_share', 'insurance_share',
        'discount', 'paid_amount',
        'requested_at', 'verified_at', 'paid_at', 'notes'
    ];
    
    protected $casts = [
        'total_amount' => 'decimal:2',
        'patient_share' => 'decimal:2',
        'insurance_share' => 'decimal:2',
        'discount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'verified_at' => 'datetime',
        'paid_at' => 'datetime'
    ];
    
    protected static function booted()
    {
        static::created(function ($serviceRequest) {
            event(new PaymentRequested($serviceRequest));
            ServiceRequestNotifier::notifyCashiers($serviceRequest);
        });

        static::updated(function ($serviceRequest) {
            if ($serviceRequest->wasChanged('payment_status') && 
                in_array($serviceRequest->payment_status, ['paid', 'insurance', 'waived'])) {
                event(new PaymentVerified($serviceRequest));
                ServiceRequestNotifier::notifyDepartmentPaymentCleared($serviceRequest);
            }
        });
    }
    
    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patients::class);
    }
    
    public function opd(): BelongsTo
    {
        return $this->belongsTo(OPD::class, 'encounter_id', 'encounter_id');
    }

    public function ipd(): BelongsTo
    {
        return $this->belongsTo(IPD::class, 'encounter_id', 'encounter_id');
    }
    
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
    
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
    
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
    
    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
    
    public function payments(): HasOne
    {
        return $this->hasOne(Payments::class);
    }

    public function laboratory(): HasOne
    {
        return $this->hasOne(Laboratory::class);
    }

    public function radiology(): HasOne
    {
        return $this->hasOne(Radiology::class);
    }
    
    // Accessors
    public function getBalanceAttribute(): float
    {
        return $this->patient_share - $this->paid_amount;
    }

    public function canProceedForService(): bool
    {
        return in_array($this->payment_status, ['paid', 'insurance', 'waived'], true);
    }
    
    public function getIsUrgentAttribute(): bool
    {
        return $this->payment_status === 'pending' && 
               $this->requested_at->diffInMinutes(now()) > 30;
    }
    
    // Actions
    public function markAsVerified(int $cashierId, ?string $notes = null): self
    {
        $this->update([
            'payment_status' => 'verified',
            'verified_by' => $cashierId,
            'verified_at' => now(),
            'notes' => $notes ?? $this->notes
        ]);
        
        return $this;
    }
    
    public function markAsPaid(int $cashierId, float $amount, string $mode, ?array $splitDetails = null): Payment
    {
        $paidAmount = $this->paid_amount + $amount;
        $paymentStatus = $paidAmount >= max(0, $this->patient_share - $this->discount)
            ? 'paid'
            : 'verified';

        $this->update([
            'payment_status' => $paymentStatus,
            'collected_by' => $cashierId,
            'paid_amount' => $paidAmount,
            'paid_at' => now(),
            'fulfillment_status' => $paymentStatus === 'paid' ? 'in_progress' : $this->fulfillment_status,
        ]);
        
        return Payments::create([
            'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . $this->id . '-' . random_int(100, 999),
            'service_request_id' => $this->id,
            'patient_id' => $this->patient_id,
            'collected_by' => $cashierId,
            'payment_mode' => $mode,
            'amount' => $amount,
            'split_details' => $splitDetails,
            'payment_date' => now()
        ]);
    }
    
    // Scopes
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }
    
    public function scopeToday($query)
    {
        return $query->whereDate('requested_at', today());
    }
    
    public function scopeForCashier($query)
    {
        return $query->with(['patient', 'service', 'requester'])
                    ->where('payment_status', 'pending')
                    ->orderBy('requested_at', 'asc');
    }
}
