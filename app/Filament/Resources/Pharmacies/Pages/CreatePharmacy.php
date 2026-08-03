<?php

namespace App\Filament\Resources\Pharmacies\Pages;

use App\Filament\Resources\Pharmacies\PharmacyResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePharmacy extends CreateRecord
{
        protected static bool $canCreateAnother=false;
    protected static string $resource = PharmacyResource::class;
}
