<?php

namespace App\Support;

use App\Models\Doctors;
use Illuminate\Database\Eloquent\Model;

class ClinicalReportAccess
{
    public static function currentDoctorId(): ?int
    {
        $user = auth()->user();

        if (! $user?->hasRole('doctor')) {
            return null;
        }

        return Doctors::query()
            ->where('user_id', $user->id)
            ->value('id');
    }

    public static function isDoctorUser(): bool
    {
        return static::currentDoctorId() !== null;
    }

    public static function canViewReport(?Model $record = null): bool
    {
        $doctorId = static::currentDoctorId();

        if ($doctorId === null) {
            return false;
        }

        if ($record === null) {
            return true;
        }

        return (int) $record->getAttribute('doctor_id') === (int) $doctorId;
    }
}
