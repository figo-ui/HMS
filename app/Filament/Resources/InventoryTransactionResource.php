<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryTransactionResource\Pages\ListInventoryTransactions;
use App\Models\InventoryTransaction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use UnitEnum;

class InventoryTransactionResource extends Resource
{
    protected static ?string $model = InventoryTransaction::class;

    protected static ?string $navigationParentItem = 'Pharmacy Management';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';
    
    protected static UnitEnum|string|null $navigationGroup = 'Inventory Management';

    protected static ?string $navigationLabel = 'Stock History';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('inventory.item_name')
                    ->label('Medicine/Item')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('quantity')
                    ->numeric()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'danger')
                    ->formatStateUsing(fn (int $state): string => ($state > 0 ? '+' : '') . $state),

                TextColumn::make('operation_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'purchase' => 'success',
                        'sale', 'dispense' => 'info',
                        'expired' => 'danger',
                        'adjustment' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('reference_id')
                    ->label('Reference')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Logged By'),

                TextColumn::make('notes')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('operation_type')
                    ->options([
                        'purchase' => 'Purchases',
                        'sale' => 'Sales',
                        'dispense' => 'Prescriptions',
                        'expired' => 'Expiry Removal',
                        'adjustment' => 'Adjustments',
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventoryTransactions::route('/'),
        ];
    }
}
