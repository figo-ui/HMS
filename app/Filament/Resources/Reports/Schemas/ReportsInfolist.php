<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Tiptap\Nodes\Details;

class ReportsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('report_id'),
                        TextEntry::make('report_type'),
                        TextEntry::make('date_range')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('filters')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('generated_by')
                            ->placeholder('-'),
                        TextEntry::make('generated_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('format')
                            ->badge(),
                    ])
                    ->columns(2),
                Section::make('Audit Trail')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2), ]);
    }
}
