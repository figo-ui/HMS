<?php

namespace App\Filament\Resources\IPDS\Schemas;

use App\Models\IPD;
use App\Models\OPD;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class IPDForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('encounter_id')
                    ->required(),
                Select::make('patient_id')
                    ->relationship('patient', 'full_name')
                    ->required(),
                Select::make('type')
                    ->options(['OPD' => 'O p d', 'IPD' => 'I p d'])
                    ->default('IPD')
                    ->required(),
                Select::make('doctor_id')
                    ->relationship('doctor', 'full_name')
                    ->required(),
                TextInput::make('department')
                    ->default(null),
                Textarea::make('diagnosis')
                    ->default(null)
                    ->columnSpanFull(),
                DatePicker::make('admission_date'),
                DatePicker::make('discharge_date'),
                Select::make('bed_id')
                    ->relationship('bed', 'id'),
                Textarea::make('treatment_plan')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('prescription_id')
                    ->default(null),
                Select::make('status')
                    ->options(['admitted' => 'Admitted', 'discharged' => 'Discharged', 'transferred' => 'Transferred'])
                    ->default('admitted')
                    ->required(),
            ]);
    }
}
