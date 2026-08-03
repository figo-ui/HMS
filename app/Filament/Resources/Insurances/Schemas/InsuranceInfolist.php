<?php

namespace App\Filament\Resources\Insurances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Tiptap\Nodes\Details;

class InsuranceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('policy_id'),
                        TextEntry::make('patient.id')
                            ->label('Patient'),
                        TextEntry::make('provider_name'),
                        TextEntry::make('policy_no'),
                        TextEntry::make('coverage_limit')
                            ->numeric(),
                        TextEntry::make('co_pay')
                            ->numeric(),
                        TextEntry::make('valid_from')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('valid_to')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('claim_id')
                            ->placeholder('-'),
                        TextEntry::make('claim_status')
                            ->badge(),
                        TextEntry::make('approved_amount')
                            ->numeric()
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
