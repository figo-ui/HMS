<?php

namespace App\Filament\Resources\IPDS\Pages;

use App\Filament\Resources\IPDS\IPDResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIPD extends CreateRecord
{    protected static bool $canCreateAnother=false;
    protected static string $resource = IPDResource::class;
}
