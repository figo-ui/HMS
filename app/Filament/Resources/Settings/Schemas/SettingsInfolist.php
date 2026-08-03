<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Tiptap\Nodes\Details;

class SettingsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('hospital_profile')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('departments')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('service_charges')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('tax_rules')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('roles_permissions')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('email_sms_settings')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('backup_settings')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('audit_id')
                            ->placeholder('-'),
                        TextEntry::make('user_id')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('action')
                            ->placeholder('-'),
                        TextEntry::make('module')
                            ->placeholder('-'),
                        TextEntry::make('record_id')
                            ->placeholder('-'),
                        TextEntry::make('old_value')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('new_value')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('ip_address')
                            ->placeholder('-'),
                    ])
                    ->columns(2),
                Section::make('Audit Trail')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2), ]);
    }
}
