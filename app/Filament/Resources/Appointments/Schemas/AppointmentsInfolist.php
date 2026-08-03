<?php

namespace App\Filament\Resources\Appointments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Tiptap\Nodes\Details;

class AppointmentsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('appointment_id'),
                        TextEntry::make('patient.id')
                            ->label('Patient'),
                        TextEntry::make('doctor.id')
                            ->label('Doctor'),
                        TextEntry::make('date')
                            ->date(),
                        TextEntry::make('time')
                            ->time(),
                        TextEntry::make('visit_type')
                            ->badge(),
                        TextEntry::make('reason')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('token_no')
                            ->placeholder('-'),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('notes')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('created_by')
                            ->placeholder('-'),
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
