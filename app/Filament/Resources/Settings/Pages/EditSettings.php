<?php

namespace App\Filament\Resources\Settings\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Settings\SettingsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\App;


class EditSettings extends EditRecord
{
    protected static string $resource = SettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
