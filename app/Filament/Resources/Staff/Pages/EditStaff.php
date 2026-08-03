<?php

namespace App\Filament\Resources\Staff\Pages;

use App\Filament\Resources\Staff\StaffResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\EditAction;
class EditStaff extends EditRecord
{
protected static bool $canCreateAnother = false;
     protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');

    }
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
