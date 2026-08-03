<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use App\Mail\WelcomeUserMail;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CreateUser extends CreateRecord
{
    protected static bool $canCreateAnother = false;

    protected static string $resource = UserResource::class;

    protected ?string $plainTextPassword = null;

    protected bool $shouldPrint = false;

    protected function getRedirectUrl(): string
    {
        if ($this->shouldPrint) {
            return route('users.print', $this->record) . '?temp_password=' . urlencode($this->plainTextPassword ?? '');
        }

        return static::getResource()::getUrl('index');
    }

     protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->plainTextPassword = $data['password'] ?? null;
        $this->shouldPrint = $data['print_info'] ?? false;

        unset($data['print_info']); // Remove from data as it's not a model field

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->email_verified_at = now();
        $this->record->save();

        try {
            Mail::to($this->record->email)->send(
                new WelcomeUserMail($this->record, $this->plainTextPassword)
            );
        } catch (Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('User created, but the welcome email could not be sent.')
                ->warning()
                ->send();
        }
    }
}
