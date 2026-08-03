<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PatientsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->description('Patient identity and registration summary.')
                    ->schema([
                        TextEntry::make('patient_id'),
                        TextEntry::make('mrn'),
                        TextEntry::make('full_name'),
                        TextEntry::make('gender')
                            ->badge(),
                        TextEntry::make('dob')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('phone')
                            ->placeholder('-'),
                        TextEntry::make('email')
                            ->label('Email address')
                            ->placeholder('-'),
                        TextEntry::make('address')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('blood_group')
                            ->badge()
                            ->placeholder('-'),
                        TextEntry::make('allergies')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('emergency_contact')
                            ->placeholder('-'),
                        TextEntry::make('insurance_id')
                            ->placeholder('-'),
                        TextEntry::make('registered_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('status')
                            ->badge(),
                    ])
                    ->columns(2),
                Section::make('Audit Trail')
                    ->description('Record tracking timestamps.')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2),
                Section::make('Patient History')
                    ->description('Recent pharmacy, encounter, and care activities.')
                    ->schema([
                        RepeatableEntry::make('histories')
                            ->schema([
                                TextEntry::make('activity')
                                    ->badge(),
                                TextEntry::make('source')
                                    ->badge(),
                                TextEntry::make('details')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                                TextEntry::make('encounter_id')
                                    ->label('Reference')
                                    ->placeholder('-'),
                                TextEntry::make('occurred_at')
                                    ->dateTime()
                                    ->placeholder('-'),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(1), ]);
    }
}
