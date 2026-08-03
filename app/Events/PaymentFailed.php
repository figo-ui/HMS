<?php

namespace App\Events;

use App\Models\ServiceRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentFailed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $serviceRequest;
    public $reason;
    public $message;
    
    /**
     * Create a new event instance.
     */
    public function __construct(ServiceRequest $serviceRequest, string $reason)
    {
        $this->serviceRequest = $serviceRequest;
        $this->reason = $reason;
        $this->message = "Payment failed for {$serviceRequest->patient->name}: {$reason}";
    }
    
    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('cashier'),
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
            'amount' => $this->serviceRequest->patient_share,
            'reason' => $this->reason,
            'message' => $this->message,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
    
    /**
     * Get the event name for broadcasting.
     */
    public function broadcastAs(): string
    {
        return 'payment.failed';
    }
}