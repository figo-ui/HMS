<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PatientPortalWelcomeNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $patientName,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Patient portal ready',
            'body' => "Welcome {$this->patientName}, your private portal account is now active.",
            'action_url' => '/admin',
        ];
    }
}
