<?php

namespace App\Filament\Resources\Nurses\Tables;

use App\Filament\Support\TableFilters;
use App\Models\Nurse;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Email;

class NursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('#')
                    ->rowIndex(),
                TextColumn::make('nurse_id')
                    ->searchable(),
                TextColumn::make('full_name')
                    ->searchable(),
                TextColumn::make('gender')
                    ->badge(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('department.name')
                    ->searchable(),
                TextColumn::make('license_no')
                    ->searchable(),
                TextColumn::make('shift')
                    ->badge(),
                TextColumn::make('join_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('emergency_contact')
                    ->searchable(),
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
                TableFilters::relationship('department', 'name', 'Department'),
                TableFilters::select('gender', [
                    'male' => 'Male',
                    'female' => 'Female',
                    'other' => 'Other',
                ]),
                TableFilters::select('shift', [
                    'day' => 'Day',
                    'night' => 'Night',
                    'rotational' => 'Rotational',
                    'custom' => 'Custom',
                ]),
                TableFilters::select('status', [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'on_leave' => 'On leave',
                ]),
                TableFilters::distinct('license_no', Nurse::class, 'License No'),
                TableFilters::dateRange('join_date', 'Join Date'),
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
