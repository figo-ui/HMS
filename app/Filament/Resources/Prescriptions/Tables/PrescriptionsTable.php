<?php

namespace App\Filament\Resources\Prescriptions\Tables;

use App\Filament\Support\TableFilters;
use App\Models\Inventory;
use App\Models\Pharmacy;
use App\Models\Prescription;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class PrescriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('#')
                    ->rowIndex(),
                TextColumn::make('arrival_flag')
                    ->label('Arrival')
                    ->state(fn (Prescription $record): string => $record->created_at?->isToday() ? 'New Today' : 'Old')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'New Today' ? 'success' : 'gray'),
                TextColumn::make('prescription_id')
                    ->searchable(),
                TextColumn::make('patient.full_name')
                    ->label('Patient')
                    ->searchable(),
                TextColumn::make('doctor.full_name')
                    ->label('Doctor')
                    ->searchable(),
                TextColumn::make('encounter_type')
                    ->badge()
                    ->sortable(),
                TextColumn::make('prescribed_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TableFilters::relationship('patient', 'full_name', 'Patient'),
                TableFilters::relationship('doctor', 'full_name', 'Doctor'),
                TableFilters::select('encounter_type', [
                    'OPD' => 'OPD',
                    'IPD' => 'IPD',
                ]),
                TableFilters::select('status', [
                    'active' => 'Active',
                    'dispensed' => 'Dispensed',
                    'expired' => 'Expired',
                    'cancelled' => 'Cancelled',
                ]),
                TableFilters::dateRange('prescribed_date', 'Prescribed Date'),
                TableFilters::createdAt(),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                Action::make('send_to_pharmacy')
                    ->label('Send to Pharmacy')
                    ->icon('heroicon-o-building-storefront')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (Prescription $record): bool => $record->status === 'active')
                    ->form([
                        Select::make('inventory_item_id')
                            ->label('Medicine From Inventory')
                            ->options(Inventory::query()->orderBy('item_name')->pluck('item_name', 'item_id')->toArray())
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (Prescription $record, array $data): void {
                        $alreadyQueued = Pharmacy::query()
                            ->where('prescription_sale_id', $record->prescription_id)
                            ->exists();

                        if ($alreadyQueued) {
                            Notification::make()
                                ->title('This prescription is already in Pharmacy queue.')
                                ->warning()
                                ->send();

                            return;
                        }

                        $inventoryItem = Inventory::query()
                            ->where('item_id', $data['inventory_item_id'] ?? '')
                            ->first();

                        if (! $inventoryItem) {
                            Notification::make()
                                ->title('Selected medicine not found in Inventory.')
                                ->danger()
                                ->send();

                            return;
                        }

                        // Validation: Check if enough stock exists
                        $qtyToDispense = 1; // Defaulting to 1; replace with dosage logic if available

                        if ($inventoryItem->quantity < $qtyToDispense) {
                            Notification::make()
                                ->title('Insufficient Stock')
                                ->body("Only {$inventoryItem->quantity} units available. Cannot dispense.")
                                ->danger()
                                ->send();

                            return;
                        }

                        Pharmacy::create([
                            'medicine_id' => $inventoryItem->item_id,
                            'medicine_name' => $inventoryItem->item_name,
                            'batch_no' => null,
                            'stock_qty' => $inventoryItem->quantity,
                            'unit_price' => $inventoryItem->selling_price,
                            'supplier_id' => $inventoryItem->supplier_id,
                            'reorder_level' => $inventoryItem->reorder_level,
                            'prescription_sale_id' => $record->prescription_id,
                            'issued_to_patient_id' => $record->patient_id,
                        ]);

                        // Log Inventory Movement (OUTPUT)
                     

                        $record->update([
                            'status' => 'dispensed',
                        ]);

                        $pharmacists = User::role('pharmacist')->get();

                        if ($pharmacists->isNotEmpty()) {
                            Notification::make()
                                ->title('New Pharmacy Queue')
                                ->body("Prescription {$record->prescription_id} for {$record->patient?->full_name} has arrived.")
                                ->icon('heroicon-o-bell-alert')
                                ->sendToDatabase($pharmacists);
                        }

                        Notification::make()
                            ->title('Prescription sent to Pharmacy.')
                            ->success()
                            ->send();
                    }),
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
