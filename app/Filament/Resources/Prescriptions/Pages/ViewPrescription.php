<?php

namespace App\Filament\Resources\Prescriptions\Pages;

use App\Filament\Resources\Prescriptions\PrescriptionResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPrescription extends ViewRecord
{
    protected static string $resource = PrescriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back')
                ->url(PrescriptionResource::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
            Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn (): string => route('prescriptions.print', ['prescription' => $this->record]))
                ->openUrlInNewTab(),
            EditAction::make(),
        ];
    }
}
