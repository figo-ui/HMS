<?php

namespace App\Filament\Resources\OPDS\Pages;

use App\Filament\Resources\OPDS\OPDResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOPD extends EditRecord
{    protected static bool $canCreateAnother=false;
    protected static string $resource = OPDResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
