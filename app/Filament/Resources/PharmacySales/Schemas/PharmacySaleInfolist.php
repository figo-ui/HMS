<?php

namespace App\Filament\Resources\PharmacySales\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PharmacySaleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sale Summary')
                    ->description('Completed pharmacy sales created from direct sales or prescription dispensing.')
                    ->schema([
                        TextEntry::make('sale_id'),
                        TextEntry::make('sale_type')->badge(),
                        TextEntry::make('patient.full_name')->label('Patient')->placeholder('-'),
                        TextEntry::make('prescription_id')->label('Prescription')->placeholder('-'),
                        TextEntry::make('medicine_name'),
                        TextEntry::make('quantity')->numeric(),
                        TextEntry::make('unit_price')->money('ETB'),
                        TextEntry::make('total_amount')->money('ETB'),
                        TextEntry::make('payment_status')->badge(),
                        TextEntry::make('seller.name')->label('Sold By')->placeholder('-'),
                        TextEntry::make('notes')->columnSpanFull()->placeholder('-'),
                        TextEntry::make('sold_at')->dateTime()->placeholder('-'),
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
