<?php

namespace App\Filament\Resources\PharmacySales\Tables;

use App\Filament\Support\TableFilters;
use App\Models\PharmacySale;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class PharmacySalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sale_id')
                    ->label('Sale ID')
                    ->searchable(),
                TextColumn::make('sale_type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title()->toString()),
                TextColumn::make('patient.full_name')
                    ->label('Patient')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('prescription_id')
                    ->label('Prescription')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('medicine_name')
                    ->label('Medicine')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->summarize(Sum::make()->label('Qty')),
                TextColumn::make('unit_price')
                    ->money('ETB'),
                TextColumn::make('total_amount')
                    ->money('ETB')
                    ->summarize(Sum::make()->money('ETB')->label('Revenue')),
                TextColumn::make('payment_status')
                    ->badge(),
                TextColumn::make('seller.name')
                    ->label('Sold By')
                    ->placeholder('-'),
                TextColumn::make('sold_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TableFilters::relationship('patient', 'full_name', 'Patient'),
                TableFilters::select('sale_type', [
                    'prescription' => 'Prescription',
                    'direct_sale' => 'Direct Sale',
                ]),
                TableFilters::select('payment_status', [
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                ]),
                TableFilters::dateRange('sold_at', 'Sold Date'),
                TableFilters::createdAt(),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
