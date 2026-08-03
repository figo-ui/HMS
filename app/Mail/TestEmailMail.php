<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $recipientEmail,
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('Hospital MS Test Email')
            ->view('emails.test-mail');
    }
}
