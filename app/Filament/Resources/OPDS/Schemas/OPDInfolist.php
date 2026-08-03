<?php

namespace App\Filament\Resources\OPDS\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OPDInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('encounter_id'),
                        TextEntry::make('patient.patient_id')
                            ->label('Patient ID')
                            ->placeholder('-'),
                        TextEntry::make('patient.full_name')
                            ->label('Patient Name')
                            ->placeholder('-'),
                        TextEntry::make('patient.phone')
                            ->label('Phone')
                            ->placeholder('-'),
                        TextEntry::make('type')
                            ->badge(),
                        TextEntry::make('doctor.full_name')
                            ->label('Doctor')
                            ->placeholder('-'),
                        TextEntry::make('department')
                            ->placeholder('-'),
                        TextEntry::make('diagnosis')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('icd10_code')
                            ->label('ICD-10 Code')
                            ->placeholder('-'),
                        TextEntry::make('admission_date')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('discharge_date')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('follow_up_date')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('bed.bed_id')
                            ->label('Bed')
                            ->placeholder('-'),
                        TextEntry::make('treatment_plan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('discharge_summary')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('prescription_id')
                            ->placeholder('-'),
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'open' => 'New Patient',
                                'closed' => 'Closed',
                                'transferred' => 'Transferred',
                                default => ucfirst($state),
                            }),
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
