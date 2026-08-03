<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointments extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResourceUrl('index');
    }
protected static string $resource = AppointmentsResource::class;
}
