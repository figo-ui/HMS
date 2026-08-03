<?php

namespace App\Events;

use App\Models\ServiceRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentVerified implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $serviceRequest;
    public $message;
    public $verifiedBy;
    
    /**
     * Create a new event instance.
     */
    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
        $this->verifiedBy = $serviceRequest->verifier?->name ?? 'Cashier';
        $this->message = "Payment verified by {$this->verifiedBy}. Service can proceed.";
    }
    
    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        $departmentChannel = $this->serviceRequest->service->service_type;
        
        return [
            new Channel("department.{$departmentChannel}"),
            new PrivateChannel('cashier'),
            new Channel('payments'),
        ];
    }
    
    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->serviceRequest->id,
            'request_number' => $this->serviceRequest->request_number,
            'patient_name' => $this->serviceRequest->patient->name,
            'service_name' => $this->serviceRequest->service->name,
            'service_type' => $this->serviceRequest->service->service_type,
            'amount' => $this->serviceRequest->patient_share,
            'status' => $this->serviceRequest->payment_status,
            'verified_by' => $this->verifiedBy,
            'verified_at' => $this->serviceRequest->verified_at?->toDateTimeString(),
            'message' => $this->message,
            'can_proceed' => true,
        ];
    }
    
    /**
     * Get the event name for broadcasting.
     */
    public function broadcastAs(): string
    {
        return 'payment.verified';
    }
}