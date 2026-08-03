<?php

namespace App\Filament\Resources\Insurances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InsuranceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('policy_id')
                    ->required(),
                Select::make('patient_id')
                    ->relationship('patient', 'id')
                    ->required(),
                TextInput::make('provider_name')
                    ->required(),
                TextInput::make('policy_no')
                    ->required(),
                TextInput::make('coverage_limit')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('co_pay')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                DatePicker::make('valid_from'),
                DatePicker::make('valid_to'),
                TextInput::make('claim_id')
                    ->default(null),
                Select::make('claim_status')
                    ->options([
                        'submitted' => 'Submitted',
                        'under_review' => 'Under review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'settled' => 'Settled',
                    ])
                    ->default('submitted')
                    ->required(),
                TextInput::make('approved_amount')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
