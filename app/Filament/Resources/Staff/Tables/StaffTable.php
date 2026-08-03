<?php

namespace App\Filament\Resources\Staff\Tables;

use App\Filament\Support\TableFilters;
use App\Models\Staff;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Email;

class StaffTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('#')
                    ->rowIndex(),
                TextColumn::make('staff_id')
                    ->searchable(),
                TextColumn::make('full_name')
                    ->searchable(),
                TextColumn::make('role')
                    ->searchable(),
                TextColumn::make('department')
                    ->searchable(),
                TextColumn::make('shift')
                    ->badge(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('join_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('salary')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
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
                TableFilters::select('role', [
                    'receptionist' => 'Receptionist',
                    'doctor' => 'Doctor',
                    'nurse' => 'Nurse',
                    'lab_technician' => 'Lab Technician',
                    'pharmacist' => 'Pharmacist',
                    'accountant' => 'Accountant',
                    'admin' => 'Admin',
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
                    'terminated' => 'Terminated',
                ]),
                TableFilters::distinct('department', Staff::class, 'Department'),
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
