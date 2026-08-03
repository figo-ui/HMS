<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReports extends CreateRecord
{
        protected static bool $canCreateAnother=false;
    protected static string $resource = ReportsResource::class;
}
