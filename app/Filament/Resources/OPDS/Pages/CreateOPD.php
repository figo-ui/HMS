<?php

namespace App\Filament\Resources\OPDS\Pages;

use App\Filament\Resources\OPDS\OPDResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOPD extends CreateRecord
{    protected static bool $canCreateAnother=false;
    protected static string $resource = OPDResource::class;
}
