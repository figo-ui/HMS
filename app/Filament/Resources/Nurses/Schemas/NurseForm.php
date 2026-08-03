<?php

namespace App\Filament\Resources\Nurses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Email;
use Livewire\Attributes\On;
use Pest\ArchPresets\Custom;

class NurseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nurse_id')
                    ->required(),
                TextInput::make('full_name')
                    ->required(),
                Select::make('gender')
                    ->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'])
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                Select::make('department_id')
                    ->relationship('department', 'name')
                    ->default(null),
                TextInput::make('license_no')
                    ->default(null),
                Select::make('shift')
                    ->options(['day' => 'Day', 'night' => 'Night', 'rotational' => 'Rotational', 'custom' => 'Custom'])
                    ->default('day')
                    ->required(),
                DatePicker::make('join_date'),
                Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive', 'on_leave' => 'On leave'])
                    ->default('active')
                    ->required(),
                TextInput::make('emergency_contact')
                    ->default(null),
            ]);
    }
}
