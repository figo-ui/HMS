<?php

namespace App\Filament\Resources\Radiologies\Schemas;

use App\Support\ClinicalReportAccess;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RadiologyForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->components([
                TextInput::make('radiology_id')
                    ->required()
                    ->maxLength(50),
                Select::make('patient_id')
                    ->relationship('patient', 'full_name')
                    ->searchable()
                    ->required(),
                Select::make('doctor_id')
                    ->relationship('doctor', 'full_name')
                    ->searchable(),
                TextInput::make('exam_name')
                    ->maxLength(120),
                TextInput::make('modality')
                    ->maxLength(60),
                DatePicker::make('exam_date'),
                Textarea::make('result_summary')
                    ->columnSpanFull()
                    ->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
                Select::make('result_status')
                    ->options([
                        'normal' => 'Normal',
                        'abnormal' => 'Abnormal',
                        'critical' => 'Critical',
                        'pending' => 'Pending',
                    ])
                    ->visible(fn (): bool => ClinicalReportAccess::isDoctorUser()),
                Select::make('status')
                    ->options([
                        'ordered' => 'Ordered',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('ordered')
                    ->required(),
            ]);
    }
}
