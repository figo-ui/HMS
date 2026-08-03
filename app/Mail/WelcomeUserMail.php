<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public ?string $plainTextPassword = null,
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('Welcome to Hospital Management System')
            ->view('emails.users.welcome');
    }
}
