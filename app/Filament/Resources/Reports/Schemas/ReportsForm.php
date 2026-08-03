<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReportsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('report_id')
                    ->required(),
                TextInput::make('report_type')
                    ->required(),
                Textarea::make('date_range')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('filters')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('generated_by')
                    ->default(null),
                DateTimePicker::make('generated_at'),
                Select::make('format')
                    ->options(['pdf' => 'Pdf', 'excel' => 'Excel', 'csv' => 'Csv'])
                    ->default('pdf')
                    ->required(),
            ]);
    }
}
