<?php

namespace App\Filament\Resources\Triages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TriageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Triage Details')
                    ->schema([
                        TextEntry::make('triage_id'),
                        TextEntry::make('patient.patient_id')->label('Patient ID'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('nurse.full_name')->label('Nurse')->placeholder('-'),
                        TextEntry::make('priority')->badge(),
                        TextEntry::make('chief_complaint')->placeholder('-'),
                        TextEntry::make('temperature')->placeholder('-'),
                        TextEntry::make('blood_pressure')->placeholder('-'),
                        TextEntry::make('pulse_rate')->placeholder('-'),
                        TextEntry::make('respiratory_rate')->placeholder('-'),
                        TextEntry::make('oxygen_saturation')->placeholder('-'),
                        TextEntry::make('status')->badge(),
                        TextEntry::make('notes')->placeholder('-')->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
