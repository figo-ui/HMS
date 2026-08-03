<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('hospital_profile')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('departments')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('service_charges')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('tax_rules')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('roles_permissions')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('email_sms_settings')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('backup_settings')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('audit_id')
                    ->default(null),
                TextInput::make('user_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('action')
                    ->default(null),
                TextInput::make('module')
                    ->default(null),
                TextInput::make('record_id')
                    ->default(null),
                Textarea::make('old_value')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('new_value')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('ip_address')
                    ->default(null),
            ]);
    }
}
