<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DoctorsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('doctor_id')
                    ->required(),
                TextInput::make('full_name')
                    ->required(),
                TextInput::make('specialization')
                    ->default(null),
                TextInput::make('license_no')
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                    Select::make('department_id')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Textarea::make('availability_schedule')
                    ->formatStateUsing(function ($state): ?string {
                        if (blank($state)) {
                            return null;
                        }

                        if (is_array($state)) {
                            return (string) ($state['notes'] ?? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                        }

                        return (string) $state;
                    })
                    ->dehydrateStateUsing(function (?string $state): ?array {
                        if (blank($state)) {
                            return null;
                        }

                        $decoded = json_decode($state, true);

                        if (json_last_error() === JSON_ERROR_NONE) {
                            return $decoded;
                        }

                        return ['notes' => trim($state)];
                    })
                    ->helperText('Enter plain text or valid JSON. Plain text will be saved safely.')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('consultation_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive', 'on_leave' => 'On leave'])
                    ->default('active')
                    ->required(),
            ]);
    }
}
