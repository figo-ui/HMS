<?php

namespace App\Events;

use App\Models\ServiceRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InsuranceClaimSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $serviceRequest;
    public $message;
    
    /**
     * Create a new event instance.
     */
    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
        $this->message = "Insurance claim submitted for {$serviceRequest->patient->name} - Amount: \${$serviceRequest->insurance_share}";
    }
    
    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('insurance'),
            new PrivateChannel('admin'),
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
            'insurance_share' => $this->serviceRequest->insurance_share,
            'patient_share' => $this->serviceRequest->patient_share,
            'status' => $this->serviceRequest->payment_status,
            'message' => $this->message,
        ];
    }
    
    /**
     * Get the event name for broadcasting.
     */
    public function broadcastAs(): string
    {
        return 'insurance.claim.submitted';
    }
}