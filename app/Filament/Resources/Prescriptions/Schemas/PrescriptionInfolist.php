<?php

namespace App\Filament\Resources\Prescriptions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Tiptap\Nodes\Details;

class PrescriptionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('prescription_id'),
                        TextEntry::make('patient.full_name')
                            ->label('Patient')
                            ->placeholder('-'),
                        TextEntry::make('doctor.full_name')
                            ->label('Doctor')
                            ->placeholder('-'),
                        TextEntry::make('encounter_type')
                            ->badge()
                            ->placeholder('-'),
                        TextEntry::make('encounter_id')
                            ->placeholder('-'),
                        TextEntry::make('prescribed_date')
                            ->date(),
                        TextEntry::make('diagnosis')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('medications')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('instructions')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('notes')
                            ->placeholder('-')
                            ->columnSpanFull(),
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
                    ->columns(2),
            ]);
    }
}
