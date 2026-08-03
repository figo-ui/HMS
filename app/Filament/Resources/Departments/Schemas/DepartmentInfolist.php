<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Email;
use Tiptap\Nodes\Details;

class DepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('department_id'),
                        TextEntry::make('name'),
                        TextEntry::make('code')->placeholder('-'),
                        TextEntry::make('description')->placeholder('-')->columnSpanFull(),
                        TextEntry::make('location')->placeholder('-'),
                        TextEntry::make('phone')->placeholder('-'),
                        TextEntry::make('email')->label('Email address')->placeholder('-'),
                        TextEntry::make('headNurse.full_name')->label('Head nurse')->placeholder('-'),
                        TextEntry::make('status')->badge(),
                    ])
                    ->columns(2),
                Section::make('Audit Trail')
                    ->schema([
                        TextEntry::make('created_at')->dateTime()->placeholder('-'),
                        TextEntry::make('updated_at')->dateTime()->placeholder('-'),
                    ])
                    ->columns(2),
            ]);
    }
}
