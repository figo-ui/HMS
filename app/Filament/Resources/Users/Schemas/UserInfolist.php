<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile Overview')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Full Name'),
                        TextEntry::make('email')
                            ->label('Email Address'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->placeholder('-'),
                        TextEntry::make('roles')
                            ->label('Roles')
                            ->formatStateUsing(fn ($record): string => $record->roles->pluck('name')->join(', '))
                            ->placeholder('-'),
                        TextEntry::make('email_verified_at')
                            ->dateTime()
                            ->label('Email Verified At')
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
