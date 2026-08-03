<?php

namespace App\Support;

use App\Mail\PatientPortalCredentialsMail;
use App\Models\Patients;
use App\Models\User;
use App\Notifications\PatientPortalWelcomeNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PatientPortalProvisioner
{
    /**
     * @return array{user: User, password: string, email_sent: bool, email: string}
     */
    public static function provision(Patients $patient, bool $sendEmail = true): array
    {
        $patientRole = PatientAccess::ensureRolePermissions();
        $plainTextPassword = Str::password(10, true, true, false, false);

        $email = filled($patient->email)
            ? (string) $patient->email
            : 'patient' . $patient->patient_id . '@portal.local';

        $user = $patient->author;

        if (! $user) {
            $user = User::query()->create([
                'name' => $patient->full_name,
                'email' => $email,
                'password' => Hash::make($plainTextPassword),
                'status' => 'new patient',
            ]);

            $user->forceFill(['email_verified_at' => now()])->save();
        } else {
            $user->forceFill([
                'name' => $patient->full_name,
                'email' => $email,
                'password' => Hash::make($plainTextPassword),
                'email_verified_at' => $user->email_verified_at ?? now(),
                'status' => $user->status ?: 'new patient',
            ])->save();
        }

        $user->syncRoles([$patientRole->name]);

        $patient->forceFill([
            'user_id' => $user->id,
            'email' => $email,
            'registered_at' => $patient->registered_at ?? now(),
        ])->save();

        $pdf = SimplePdf::textDocument('Patient Portal Credentials', [
            'Patient Name: ' . $patient->full_name,
            'Patient ID: ' . $patient->patient_id,
            'Portal URL: ' . url('/admin/login'),
            'Email: ' . $user->email,
            'Temporary Password: ' . $plainTextPassword,
            'Please sign in and change your password after first login.',
        ]);

        $emailSent = false;

        if ($sendEmail && filled($user->email) && ! str_ends_with($user->email, '@portal.local')) {
            Mail::to($user->email)->send(
                new PatientPortalCredentialsMail($patient, $user, $plainTextPassword, $pdf)
            );

            $emailSent = true;
        }

        $user->notify(new PatientPortalWelcomeNotification($patient->full_name));

        return [
            'user' => $user,
            'password' => $plainTextPassword,
            'email_sent' => $emailSent,
            'email' => $user->email,
        ];
    }
}
