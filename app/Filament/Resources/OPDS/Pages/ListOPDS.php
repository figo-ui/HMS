<?php

namespace App\Filament\Resources\OPDS\Pages;

use App\Filament\Resources\OPDS\OPDResource;
use Filament\Resources\Pages\ListRecords;

class ListOPDS extends ListRecords
{
    protected static string $resource = OPDResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
