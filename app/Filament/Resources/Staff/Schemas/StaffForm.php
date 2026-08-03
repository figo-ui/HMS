<?php

namespace App\Filament\Resources\Staff\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('staff_id')
                    ->required(),
                TextInput::make('full_name')
                    ->required(),
                Select::make('role')
                    ->options([
                        'receptionist' => 'Receptionist',
                        'doctor' => 'Doctor',
                        'nurse' => 'Nurse',
                        'lab_technician' => 'Lab Technician',
                        'pharmacist' => 'Pharmacist',
                        'accountant' => 'Accountant',
                        'admin' => 'Admin',
                    ])
                    ->searchable()
                    ->required(),
                TextInput::make('department')
                    ->default(null),
                Select::make('shift')
                    ->options(['day' => 'Day', 'night' => 'Night', 'rotational' => 'Rotational', 'custom' => 'Custom'])
                    ->default('day')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                DatePicker::make('join_date'),
                TextInput::make('salary')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'on_leave' => 'On leave',
                        'terminated' => 'Terminated',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }
}
