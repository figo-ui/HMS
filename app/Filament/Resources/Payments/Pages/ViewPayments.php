<?php

namespace App\Filament\Resources\Payments\Pages;


use App\Filament\Resources\Payments\PaymentsResource;


use Filament\Actions\EditAction;

use Filament\Resources\Pages\ViewRecord;


class ViewPayments extends ViewRecord
{
    protected static string $resource = PaymentsResource::class;

    protected function getHeaderActions(): array
    {
       
            return [
               
                EditAction::make(),
            ];
    }

    
}
