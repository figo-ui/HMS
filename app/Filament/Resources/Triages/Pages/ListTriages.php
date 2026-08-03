<?php

namespace App\Filament\Resources\Triages\Pages;

use App\Filament\Resources\Triages\TriageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTriages extends ListRecords
{
    protected static string $resource = TriageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
