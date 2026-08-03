<?php

namespace App\Filament\Resources\Pharmacies\Schemas;

use App\Models\Inventory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PharmacyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('medicine_id')
                    ->label('Medicine (Inventory Item)')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search): array {
                        return Inventory::query()
                            ->where(function ($query) use ($search): void {
                                $query
                                    ->where('item_name', 'like', "%{$search}%")
                                    ->orWhere('item_id', 'like', "%{$search}%");
                            })
                            ->orderBy('item_name')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn (Inventory $item): array => [
                                $item->item_id => "{$item->item_name} ({$item->item_id})",
                            ])
                            ->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        if (! filled($value)) {
                            return null;
                        }

                        $item = Inventory::query()
                            ->where('item_id', (string) $value)
                            ->first();

                        return $item ? "{$item->item_name} ({$item->item_id})" : (string) $value;
                    })
                    ->live()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set): void {
                        $item = Inventory::query()->where('item_id', $state)->first();

                        if (! $item) {
                            return;
                        }

                        $set('medicine_name', $item->item_name);
                        $set('supplier_id', $item->supplier_id);
                        $set('unit_price', $item->selling_price);
                        $set('reorder_level', $item->reorder_level);
                    }),
                TextInput::make('medicine_name')
                    ->required()
                    ->readOnly(),
                TextInput::make('batch_no')
                    ->default(null),
                DatePicker::make('expiry_date'),
                TextInput::make('stock_qty')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('unit_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('supplier_id')
                    ->default(null),
                TextInput::make('reorder_level')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('prescription_sale_id')
                    ->default(null),
                Select::make('issued_to_patient_id')
                    ->relationship('patient', 'full_name')
                    ->searchable(),
            ]);
    }
}
