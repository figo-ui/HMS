<?php

namespace App\Filament\Resources\Doctors\Pages;

use App\Filament\Resources\Doctors\DoctorsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDoctors extends CreateRecord
{    protected static bool $canCreateAnother=false;
protected function getRedirectUrl(): string
    {
        return $this->getResourceUrl('index');
    }
    protected static string $resource = DoctorsResource::class;
}
