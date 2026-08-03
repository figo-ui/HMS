<?php

namespace App\Filament\Resources\Staff\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Staff\StaffResource;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\App;


class CreateStaff extends CreateRecord
{
    
    protected static string $resource = StaffResource::class;
 protected static bool $canCreateAnother = false;
     protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');

    }

}
