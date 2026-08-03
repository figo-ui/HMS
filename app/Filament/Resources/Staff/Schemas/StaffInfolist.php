<?php

namespace App\Filament\Resources\Staff\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Email;
use Tiptap\Nodes\Details;

class StaffInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('staff_id'),
                        TextEntry::make('full_name'),
                        TextEntry::make('role'),
                        TextEntry::make('department')
                            ->placeholder('-'),
                        TextEntry::make('shift')
                            ->badge(),
                        TextEntry::make('phone')
                            ->placeholder('-'),
                        TextEntry::make('email')
                            ->label('Email address')
                            ->placeholder('-'),
                        TextEntry::make('join_date')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('salary')
                            ->numeric(),
                        TextEntry::make('status')
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
