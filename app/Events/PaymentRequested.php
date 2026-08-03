<?php

namespace App\Events;

use App\Models\ServiceRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentRequested implements ShouldBroadcast
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
        $this->message = "Payment request created for {$serviceRequest->patient->name} - Amount: \${$serviceRequest->patient_share}";
    }
    
    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        // Broadcast to cashier channel for real-time updates
        return [
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
            'patient_id' => $this->serviceRequest->patient_id,
            'service_name' => $this->serviceRequest->service->name,
            'service_type' => $this->serviceRequest->service->service_type,
            'amount' => $this->serviceRequest->patient_share,
            'total_amount' => $this->serviceRequest->total_amount,
            'insurance_share' => $this->serviceRequest->insurance_share,
            'status' => $this->serviceRequest->payment_status,
            'requested_at' => $this->serviceRequest->requested_at->toDateTimeString(),
            'requested_by' => $this->serviceRequest->requester->name,
            'message' => $this->message,
            'time_ago' => $this->serviceRequest->requested_at->diffForHumans(),
        ];
    }
    
    /**
     * Get the event name for broadcasting.
     */
    public function broadcastAs(): string
    {
        return 'payment.requested';
    }
}