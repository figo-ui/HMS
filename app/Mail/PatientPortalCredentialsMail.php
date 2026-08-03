<?php

namespace App\Mail;

use App\Models\Patients;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class PatientPortalCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Patients $patient,
        public User $user,
        public string $plainTextPassword,
        public string $pdfContent,
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('Your Patient Portal Account Is Ready')
            ->view('emails.patients.portal-credentials');
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn (): string => $this->pdfContent, 'patient-portal-credentials.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
