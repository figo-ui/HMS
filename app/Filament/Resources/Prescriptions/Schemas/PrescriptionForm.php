<?php

namespace App\Filament\Resources\Prescriptions\Schemas;

use App\Models\IPD;
use App\Models\OPD;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PrescriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('prescription_id')
                    ->required()
                    ->maxLength(50),
                Select::make('patient_id')
                    ->relationship('patient', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('doctor_id')
                    ->relationship('doctor', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('encounter_type')
                    ->options([
                        'OPD' => 'OPD',
                        'IPD' => 'IPD',
                    ])
                    ->placeholder('Select encounter type'),
                TextInput::make('encounter_id')
                    ->maxLength(50),
                DatePicker::make('prescribed_date')
                    ->required(),
                Textarea::make('diagnosis')
                    ->rows(3)
                    ->columnSpanFull(),
                KeyValue::make('medications')
                    ->keyLabel('Medicine')
                    ->valueLabel('Dose / Frequency')
                    ->columnSpanFull(),
                Textarea::make('instructions')
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'dispensed' => 'Dispensed',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }
}
