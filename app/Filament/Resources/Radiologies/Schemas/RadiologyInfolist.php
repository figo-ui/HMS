<?php

namespace App\Filament\Resources\Radiologies\Schemas;

use App\Support\ClinicalReportAccess;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\Action;

class RadiologyInfolist
{
    public static function configure(Schema $infolist): Schema
    {
        return $infolist
            ->components([
                Section::make('Radiology Details')
                    ->schema([
                        TextEntry::make('radiology_id'),
                        TextEntry::make('patient.patient_id')->label('Patient ID'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('doctor.full_name')->label('Doctor')->placeholder('-'),
                        TextEntry::make('exam_name')->placeholder('-'),
                        TextEntry::make('modality')->placeholder('-'),
                        TextEntry::make('exam_date')->date()->placeholder('-'),
                        TextEntry::make('status')->badge(),
                    ])
                    ->columns(2),

                Section::make('Radiology Findings')
                    ->description('Detailed clinical findings and conclusions')
                    ->headerActions([
                        Action::make('print')
                            ->label('Print Report')
                            ->icon('heroicon-o-printer')
                            ->extraAttributes(['onclick' => 'window.print(); return false;']),
                       
                    ])
                    ->schema([
                        TextEntry::make('result_status')->badge()->placeholder('-'),
                        TextEntry::make('completed_at')->label('Result Date')->dateTime()->placeholder('-'),
                        TextEntry::make('result_summary')->placeholder('-')->columnSpanFull(),
                        TextEntry::make('findings')
                            ->prose()
                            ->columnSpanFull(),
                        TextEntry::make('conclusion')
                            ->weight('bold')
                            ->columnSpanFull(),
                        ImageEntry::make('report_image')
                            ->label('Scan / X-Ray Image')
                            ->size(400)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record): bool => $record->status === 'completed' && ClinicalReportAccess::canViewReport($record)),
            ]);
    }
}
