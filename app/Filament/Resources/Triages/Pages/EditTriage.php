<?php

namespace App\Filament\Resources\Triages\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Triages\TriageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;

class EditTriage extends EditRecord
{
    protected static string $resource = TriageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
