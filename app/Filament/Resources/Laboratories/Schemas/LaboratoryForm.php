<?php

namespace App\Filament\Resources\Laboratories\Schemas;

use App\Support\ClinicalReportAccess;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LaboratoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('lab_id')
                    ->default(null),
                Select::make('patient_id')
                    ->relationship('patient', 'id')
                    ->default(null),
                Select::make('doctor_id')
                    ->relationship('doctor', 'id')
                    ->default(null),
                TextInput::make('test_name')
                    ->default(null),
                TextInput::make('test_type')
                    ->default(null),
                TextInput::make('sample_type')
                    ->default(null),
                DatePicker::make('test_date'),
                DatePicker::make('result_date')
                    ->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
                Textarea::make('result_value')
                    ->default(null)
                    ->columnSpanFull()
                    ->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
                Select::make('result_status')
                    ->options([
                        'normal' => 'Normal',
                        'abnormal' => 'Abnormal',
                        'critical' => 'Critical',
                        'pending' => 'Pending',
                    ])
                    ->default(null)
                    ->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
                TextInput::make('normal_range')
                    ->default(null)
                    ->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull()
                    ->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
                Select::make('status')
                    ->options([
                        'ordered' => 'Ordered',
                        'sample_collected' => 'Sample collected',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('ordered')
                    ->required(),
            ]);
    }
}
