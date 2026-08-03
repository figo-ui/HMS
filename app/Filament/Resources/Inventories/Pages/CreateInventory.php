<?php

namespace App\Filament\Resources\Inventories\Pages;

use App\Filament\Resources\Inventories\InventoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInventory extends CreateRecord
{    protected static bool $canCreateAnother=false;
    protected static string $resource = InventoryResource::class;
 protected function getRedirectUrl(): string
    {
        return $this->getResourceUrl('index');
    }
}
