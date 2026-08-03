<?php

namespace App\Filament\Resources\Inventories\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Tiptap\Nodes\Details;

class InventoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('item_id'),
                        TextEntry::make('item_name'),
                        TextEntry::make('category')
                            ->placeholder('-'),
                        TextEntry::make('unit')
                            ->placeholder('-'),
                        TextEntry::make('quantity')
                            ->numeric(),
                        TextEntry::make('reorder_level')
                            ->numeric(),
                        TextEntry::make('supplier_id')
                            ->placeholder('-'),
                        TextEntry::make('purchase_price')
                            ->money(),
                        TextEntry::make('selling_price')
                            ->money(),
                        TextEntry::make('expiry_date')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('store_location')
                            ->placeholder('-'),
                        TextEntry::make('stock_movement_log')
                            ->placeholder('-')
                            ->columnSpanFull(),
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
