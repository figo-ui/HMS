<?php

namespace App\Events;

use App\Models\Payment;
use App\Models\ServiceRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $payment;
    public $serviceRequest;
    public $message;
    
    /**
     * Create a new event instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        $this->serviceRequest = $payment->serviceRequest;
        $this->message = "Payment completed: {$payment->invoice_number} - Amount: \${$payment->amount}";
    }
    
    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('cashier'),
            new Channel('payments'),
            new PrivateChannel('admin'),
        ];
    }
    
    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'payment_id' => $this->payment->id,
            'invoice_number' => $this->payment->invoice_number,
            'patient_name' => $this->payment->patient->name,
            'amount' => $this->payment->amount,
            'payment_mode' => $this->payment->payment_mode,
            'payment_date' => $this->payment->payment_date->toDateTimeString(),
            'collected_by' => $this->payment->collector->name,
            'request_number' => $this->serviceRequest->request_number,
            'message' => $this->message,
        ];
    }
    
    /**
     * Get the event name for broadcasting.
     */
    public function broadcastAs(): string
    {
        return 'payment.completed';
    }
}