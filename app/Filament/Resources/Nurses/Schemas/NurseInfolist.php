<?php

namespace App\Filament\Resources\Nurses\Schemas;

use App\Models\Department;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Email;
use Tiptap\Nodes\Details;

class NurseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('nurse_id'),
                        TextEntry::make('full_name'),
                        TextEntry::make('gender')->badge(),
                        TextEntry::make('phone')->placeholder('-'),
                        TextEntry::make('email')->label('Email address')->placeholder('-'),
                        TextEntry::make('department.name')->label('Department')->placeholder('-'),
                        TextEntry::make('license_no')->placeholder('-'),
                        TextEntry::make('shift')->badge(),
                        TextEntry::make('join_date')->date()->placeholder('-'),
                        TextEntry::make('status')->badge(),
                        TextEntry::make('emergency_contact')->placeholder('-'),
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
