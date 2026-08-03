<?php

namespace App\Filament\Resources\Radiologies\Pages;

use App\Filament\Resources\Radiologies\RadiologyResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRadiology extends ViewRecord
{
    protected static string $resource = RadiologyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back')
                ->url(RadiologyResource::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
            EditAction::make(),
        ];
    }
}
