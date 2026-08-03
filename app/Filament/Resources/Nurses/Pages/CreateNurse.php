<?php

namespace App\Filament\Resources\Nurses\Pages;

use App\Filament\Resources\Nurses\NurseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNurse extends CreateRecord
{    protected static bool $canCreateAnother=false;    protected static string $resource = NurseResource::class;
}
