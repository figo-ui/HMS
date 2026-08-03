<?php

namespace App\Filament\Resources\Insurances\Tables;

use App\Filament\Support\TableFilters;
use App\Models\Insurance;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class InsurancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('#')
                    ->rowIndex(),
                TextColumn::make('policy_id')
                    ->searchable(),
                TextColumn::make('patient.id')
                    ->searchable(),
                TextColumn::make('provider_name')
                    ->searchable(),
                TextColumn::make('policy_no')
                    ->searchable(),
                TextColumn::make('coverage_limit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('co_pay')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('valid_from')
                    ->date()
                    ->sortable(),
                TextColumn::make('valid_to')
                    ->date()
                    ->sortable(),
                TextColumn::make('claim_id')
                    ->searchable(),
                TextColumn::make('claim_status')
                    ->badge(),
                TextColumn::make('approved_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TableFilters::relationship('patient', 'full_name', 'Patient'),
                TableFilters::distinct('provider_name', Insurance::class, 'Provider'),
                TableFilters::select('claim_status', [
                    'submitted' => 'Submitted',
                    'under_review' => 'Under review',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'settled' => 'Settled',
                ]),
                TableFilters::dateRange('valid_from', 'Valid From'),
                TableFilters::dateRange('valid_to', 'Valid To'),
                TableFilters::createdAt(),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
