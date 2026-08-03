<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSettings extends CreateRecord
{
        protected static bool $canCreateAnother=false;
    protected static string $resource = SettingsResource::class;
}
