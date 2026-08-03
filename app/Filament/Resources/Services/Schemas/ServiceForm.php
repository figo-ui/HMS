<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service')
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(150),
                        Select::make('service_type')
                            ->options([
                                'lab' => 'Laboratory',
                                'radiology' => 'Radiology',
                                'pharmacy' => 'Pharmacy',
                                'consultation' => 'Consultation',
                                'physiotherapy' => 'Physiotherapy',
                                'surgery' => 'Surgery',
                                'bed_charges' => 'Bed Charges',
                                'emergency' => 'Emergency',
                            ])
                            ->required(),
                        Select::make('department_id')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->default(0),
                        TextInput::make('insurance_coverage_percent')
                            ->label('Insurance %')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0),
                        Toggle::make('requires_pre_auth')
                            ->label('Requires pre-authorization'),
                        Toggle::make('is_active')
                            ->default(true),
                    ])
                    ->columns(4),
            ]);
    }
}
