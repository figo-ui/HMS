<?php

namespace App\Filament\Resources\Laboratories\Pages;

use App\Filament\Resources\Laboratories\LaboratoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLaboratory extends CreateRecord
{
        protected static bool $canCreateAnother=false;
    protected static string $resource = LaboratoryResource::class;
}
