<?php

namespace App\Support;

use App\Models\Patients;
use App\Models\User;

class PatientPortal
{
    public static function currentPatient(): ?Patients
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (! $user || ! $user->hasRole('patient')) {
            return null;
        }

        return Patients::query()
            ->where('user_id', $user->id)
            ->first();
    }

    public static function currentPatientId(): ?int
    {
        return static::currentPatient()?->getKey();
    }

    public static function isPatientUser(): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        return (bool) $user?->hasRole('patient');
    }
}
