<?php

namespace App\Filament\Resources\Inventories\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;


class InventoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('item_id')
                    ->required(),
                TextInput::make('item_name')
                    ->required(),
                TextInput::make('category')
                    ->default(null),
                TextInput::make('unit')
                    ->default(null),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('reorder_level')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('supplier_id')
                    ->default(null),
                TextInput::make('purchase_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('ETB'),
                TextInput::make('selling_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('ETB'),
                DatePicker::make('expiry_date'),
                TextInput::make('store_location')
                    ->default(null),
                Textarea::make('stock_movement_log')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
