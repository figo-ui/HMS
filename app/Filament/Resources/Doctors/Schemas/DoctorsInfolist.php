<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DoctorsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('doctor_id'),
                        TextEntry::make('full_name'),
                        TextEntry::make('specialization')
                            ->placeholder('-'),
                        TextEntry::make('license_no')
                            ->placeholder('-'),
                        TextEntry::make('phone')
                            ->placeholder('-'),
                        TextEntry::make('email')
                            ->label('Email address')
                            ->placeholder('-'),
                        TextEntry::make('department')
                            ->placeholder('-'),
                        TextEntry::make('availability_schedule')
                            ->formatStateUsing(function ($state): ?string {
                                if (blank($state)) {
                                    return null;
                                }

                                if (is_array($state)) {
                                    return (string) ($state['notes'] ?? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                                }

                                return (string) $state;
                            })
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('consultation_fee')
                            ->numeric(),
                        TextEntry::make('status')
                            ->badge(),
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
