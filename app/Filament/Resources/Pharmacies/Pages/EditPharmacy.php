<?php

namespace App\Filament\Resources\Pharmacies\Pages;

use App\Filament\Resources\Pharmacies\PharmacyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPharmacy extends EditRecord
{
    protected static string $resource = PharmacyResource::class;
    protected static bool $canCreateAnother=false;
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            
            DeleteAction::make(),
        ];
    }
}
