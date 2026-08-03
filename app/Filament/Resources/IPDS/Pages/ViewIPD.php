<?php

namespace App\Filament\Resources\IPDS\Pages;
use App\Filament\Resources\IPDS\IPDResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
class ViewIPD extends ViewRecord
{
    protected static string $resource = IPDResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back')
                ->url(IPDResource::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),

            
        
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
