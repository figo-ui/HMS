<?php

namespace App\Filament\Resources\Laboratories\Schemas;

use App\Support\ClinicalReportAccess;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LaboratoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('lab_id')->placeholder('-'),
                        TextEntry::make('patient.full_name')->label('Patient')->placeholder('-'),
                        TextEntry::make('doctor.full_name')->label('Doctor')->placeholder('-'),
                        TextEntry::make('test_name')->placeholder('-'),
                        TextEntry::make('test_type')->placeholder('-'),
                        TextEntry::make('sample_type')->placeholder('-'),
                        TextEntry::make('test_date')->date()->placeholder('-'),
                        TextEntry::make('cost')->money()->placeholder('-'),
                        TextEntry::make('status')->badge(),
                    ])
                    ->columns(2),
                Section::make('Laboratory Report')
                    ->schema([
                        TextEntry::make('result_date')->date()->placeholder('-'),
                        TextEntry::make('result_status')->badge()->placeholder('-'),
                        TextEntry::make('normal_range')->placeholder('-'),
                        TextEntry::make('result_value')->placeholder('-')->columnSpanFull(),
                        TextEntry::make('notes')->placeholder('-')->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn ($record): bool => ClinicalReportAccess::canViewReport($record)),
                Section::make('Audit Trail')
                    ->schema([
                        TextEntry::make('created_at')->dateTime()->placeholder('-'),
                        TextEntry::make('updated_at')->dateTime()->placeholder('-'),
                    ])
                    ->columns(2),
            ]);
    }
}
