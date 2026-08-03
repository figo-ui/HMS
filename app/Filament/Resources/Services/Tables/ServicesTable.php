<?php

namespace App\Filament\Resources\Services\Tables;

use App\Filament\Support\TableFilters;
use App\Models\Service;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service_type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title()->toString()),
                TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable(),
                TextColumn::make('price')
                    ->money('usd')
                    ->sortable(),
                TextColumn::make('insurance_coverage_percent')
                    ->label('Insurance %')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                IconColumn::make('requires_pre_auth')
                    ->label('Pre-auth')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                TableFilters::select('service_type', [
                    'lab' => 'Laboratory',
                    'radiology' => 'Radiology',
                    'pharmacy' => 'Pharmacy',
                    'consultation' => 'Consultation',
                    'physiotherapy' => 'Physiotherapy',
                    'surgery' => 'Surgery',
                    'bed_charges' => 'Bed Charges',
                    'emergency' => 'Emergency',
                ]),
                TableFilters::relationship('department', 'name', 'Department'),
                TableFilters::select('is_active', [
                    '1' => 'Active',
                    '0' => 'Inactive',
                ]),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
