<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Filament\Resources\Patients\PatientsResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use App\Support\PatientPortalProvisioner;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreatePatients extends CreateRecord
{
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return $this->getResourceUrl('index');
    }
    protected static string $resource = PatientsResource::class;
/*
    protected ?User $patientUser = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $email = filled($data['email'] ?? null)
            ? (string) $data['email']
            : 'patient' . ($data['patient_id'] ?? Str::random(6)) . '@portal.local';

        $this->patientUser = User::query()->create([
            'name' => $data['full_name'],
            'email' => $email,
            'password' => Hash::make(Str::random(24)),
        ]);

        $data['user_id'] = $this->patientUser->id;
        $data['email'] = $email;
        $data['registered_at'] = $data['registered_at'] ?? now();

        return $data;
    }

    protected function afterCreate(): void
    {
        try {
            $result = PatientPortalProvisioner::provision($this->record);
            $this->patientUser = $result['user'];
        } catch (\Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('Patient created, but portal account provisioning failed.')
                ->warning()
                ->send();
        }
    }*/
}
