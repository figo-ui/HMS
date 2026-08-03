<?php

namespace App\Filament\Resources\IPDS\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Tiptap\Nodes\Details;

class IPDInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('encounter_id'),
                        TextEntry::make('patient.id')
                            ->label('Patient'),
                        TextEntry::make('type')
                            ->badge(),
                        TextEntry::make('doctor.id')
                            ->label('Doctor'),
                        TextEntry::make('department')
                            ->placeholder('-'),
                        TextEntry::make('diagnosis')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('admission_date')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('discharge_date')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('bed.id')
                            ->label('Bed')
                            ->placeholder('-'),
                        TextEntry::make('treatment_plan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('prescription_id')
                            ->placeholder('-'),
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
