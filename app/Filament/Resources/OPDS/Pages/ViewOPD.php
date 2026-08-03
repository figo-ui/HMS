<?php

namespace App\Filament\Resources\OPDS\Pages;

use App\Filament\Resources\OPDS\OPDResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
class ViewOPD extends ViewRecord
{
    protected static string $resource = OPDResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back')
                ->url(OPDResource::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
            EditAction::make(),
        ];
    }
}
