<?php

namespace App\Filament\Resources\Radiologies\Pages;

use App\Filament\Resources\Radiologies\RadiologyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRadiologies extends ListRecords
{
    protected static string $resource = RadiologyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
