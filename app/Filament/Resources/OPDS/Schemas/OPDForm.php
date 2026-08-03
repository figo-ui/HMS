<?php

namespace App\Filament\Resources\OPDS\Schemas;

use App\Models\IPD;
use App\Models\OPD;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OPDForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('encounter_id')
                    ->required(),
                Select::make('patient_id')
                    ->relationship('patient', 'id')
                    ->required(),
                Select::make('type')
                    ->options(['OPD' => 'O p d', 'IPD' => 'I p d'])
                    ->default('OPD')
                    ->required(),
                Select::make('doctor_id')
                    ->relationship('doctor', 'id')
                    ->required(),
                TextInput::make('department')
                    ->default(null),
                Textarea::make('diagnosis')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('icd10_code')
                    ->label('ICD-10 Code')
                    ->maxLength(20)
                    ->default(null),
                DatePicker::make('admission_date'),
                DatePicker::make('discharge_date'),
                DatePicker::make('follow_up_date'),
                Select::make('bed_id')
                    ->relationship('bed', 'id'),
                Textarea::make('treatment_plan')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('discharge_summary')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('prescription_id')
                    ->default(null),
                Select::make('status')
                    ->options(['open' => 'Open', 'closed' => 'Closed', 'transferred' => 'Transferred'])
                    ->default('open')
                    ->required(),
            ]);
    }
}
