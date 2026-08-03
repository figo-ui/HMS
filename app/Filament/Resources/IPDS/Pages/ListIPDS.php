<?php

namespace App\Filament\Resources\IPDS\Pages;

use App\Filament\Resources\IPDS\IPDResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIPDS extends ListRecords
{
    protected static string $resource = IPDResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
