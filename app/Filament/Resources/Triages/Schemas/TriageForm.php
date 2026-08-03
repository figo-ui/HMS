<?php

namespace App\Filament\Resources\Triages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TriageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('triage_id')
                    ->required()
                    ->maxLength(50),
                Select::make('patient_id')
                    ->relationship('patient', 'full_name')
                    ->searchable()
                    ->required(),
                Select::make('nurse_id')
                    ->relationship('nurse', 'full_name')
                    ->searchable(),
                TextInput::make('encounter_id')
                    ->maxLength(50),
                Select::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'critical' => 'Critical',
                    ])
                    ->default('medium')
                    ->required(),
                TextInput::make('chief_complaint')
                    ->maxLength(255),
                TextInput::make('temperature')
                    ->maxLength(20),
                TextInput::make('blood_pressure')
                    ->maxLength(20),
                TextInput::make('pulse_rate')
                    ->numeric(),
                TextInput::make('respiratory_rate')
                    ->numeric(),
                TextInput::make('oxygen_saturation')
                    ->numeric(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'waiting' => 'Waiting',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                       
                    ])
                    ->default('waiting')
                    ->required(),
            ]);
    }
}
