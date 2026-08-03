<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PatientsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Patient Identity')
                    ->description('Primary registration details for the patient.')
                    ->schema([
                        TextInput::make('patient_id')
                            ->label('Patient Code')
                            ->required(),
                        TextInput::make('mrn')
                            ->label('MRN')
                            ->required(),
                        TextInput::make('full_name')
                            ->label('Full Name')
                            ->required(),
                        Select::make('gender')
                            ->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'])
                            ->required(),
                        DatePicker::make('dob')
                            ->label('Date of Birth'),
                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'deceased' => 'Deceased',
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columns(3),
                Section::make('Contact & Medical Notes')
                    ->description('Useful contact details and care context.')
                    ->schema([
                        TextInput::make('phone')
                            ->tel()
                            ->default(null),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->default(null),
                        TextInput::make('emergency_contact')
                            ->default(null),
                        Select::make('blood_group')
                            ->options([
                                'A+' => 'A+',
                                'A-' => 'A-',
                                'B+' => 'B+',
                                'B-' => 'B-',
                                'AB+' => 'AB+',
                                'AB-' => 'AB-',
                                'O+' => 'O+',
                                'O-' => 'O-',
                            ])
                            ->default(null),
                        TextInput::make('insurance_id')
                            ->label('Insurance Reference')
                            ->default(null),
                        DateTimePicker::make('registered_at')
                            ->label('Registered At'),
                        Textarea::make('address')
                            ->default(null)
                            ->columnSpanFull(),
                        Textarea::make('allergies')
                            ->default(null)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }
}
