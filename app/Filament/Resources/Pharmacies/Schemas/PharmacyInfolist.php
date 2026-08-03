<?php

namespace App\Filament\Resources\Pharmacies\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
class PharmacyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->description('Pharmacy dispensing and sales records linked to patient prescriptions and inventory.')
                    ->schema([
                        TextEntry::make('medicine_id'),
                        TextEntry::make('medicine_name'),
                        TextEntry::make('batch_no')
                            ->placeholder('-'),
                        TextEntry::make('expiry_date')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('stock_qty')
                            ->numeric(),
                        TextEntry::make('unit_price')
                            ->money(),
                        TextEntry::make('supplier_id')
                            ->placeholder('-'),
                        TextEntry::make('reorder_level')
                            ->numeric(),
                        TextEntry::make('prescription_sale_id')
                            ->placeholder('-'),
                        TextEntry::make('issued_to_patient_id')
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
