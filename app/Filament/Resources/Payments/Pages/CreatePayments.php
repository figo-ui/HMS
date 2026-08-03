<?php

namespace App\Filament\Resources\Payments\Pages;

use App\Filament\Resources\Payments\PaymentsResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePayments extends CreateRecord
{
    protected static string $resource = PaymentsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Payments Recorded')
            ->body('The new payments record has been successfully created.')
            ->icon('heroicon-o-check-circle');
    }

    public function getTitle(): string
    {
        return 'Create Payments';
    }
}
