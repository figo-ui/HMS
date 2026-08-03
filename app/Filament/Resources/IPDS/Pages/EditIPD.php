<?php

namespace App\Filament\Resources\IPDS\Pages;

use App\Filament\Resources\IPDS\IPDResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditIPD extends EditRecord
{
    protected static string $resource = IPDResource::class;
    protected static bool $canCreateAnother=false;
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
