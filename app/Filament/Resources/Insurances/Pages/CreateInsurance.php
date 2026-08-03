<?php

namespace App\Filament\Resources\Insurances\Pages;

use App\Filament\Resources\Insurances\InsuranceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInsurance extends CreateRecord
{    protected static bool $canCreateAnother=false;
protected function getRedirectUrl(): string
    {
        return $this->getResourceUrl('index');
    }
    protected static string $resource = InsuranceResource::class;
}
