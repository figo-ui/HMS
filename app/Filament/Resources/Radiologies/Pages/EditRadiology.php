<?php

namespace App\Filament\Resources\Radiologies\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Radiologies\RadiologyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;

class EditRadiology extends EditRecord
{
    protected static string $resource = RadiologyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
