<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back')
                ->url(UserResource::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
            Action::make('reset_password')
                ->label('Reset Password')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Reset User Password')
                ->modalDescription('This will send a password reset link to the user. Are you sure?')
                ->modalSubmitActionLabel('Send Reset Link')
                ->action(function () {
                    $this->record->sendPasswordResetNotification(
                        \Illuminate\Support\Facades\Password::createToken($this->record)
                    );
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Password reset link sent')
                        ->body("A password reset link has been sent to {$this->record->email}")
                        ->success()
                        ->send();
                }),
            Action::make('print')
                ->label('Print User Info')
                ->url(fn ($record) => route('users.print', $record))
                ->openUrlInNewTab()
                ->color('success')
                ->icon('heroicon-o-printer'),
        ];
    }
}
