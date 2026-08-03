<?php

namespace App\Filament\Resources\Pharmacies\Tables;

use App\Filament\Support\TableFilters;
use App\Models\PatientHistory;
use App\Models\Patients;
use App\Models\Pharmacy;
use App\Models\PharmacySale;
use App\Models\PharmacyMovement;
use App\Models\Prescription;
use App\Services\InventoryService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Throwable;

class PharmaciesTable
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
                    ->state(fn (Pharmacy $record): string => $record->created_at?->isToday() ? 'New Today' : 'Old')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'New Today' ? 'success' : 'gray'),
                TextColumn::make('medicine_id')
                    ->searchable(),
                TextColumn::make('medicine_name')
                    ->searchable(),
                TextColumn::make('queue_status')
                    ->label('Status')
                    ->state(fn (Pharmacy $record): string => $record->stock_qty > 0 ? 'Awaiting Dispense' : 'Dispensed')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Awaiting Dispense' ? 'warning' : 'success'),
                TextColumn::make('batch_no')
                    ->searchable(),
                TextColumn::make('expiry_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('stock_qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->money()
                    ->sortable(),
                TextColumn::make('supplier_id')
                    ->searchable(),
                TextColumn::make('reorder_level')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('prescription_sale_id')
                    ->label('Prescription ID')
                    ->searchable(),
                TextColumn::make('issued_to_patient_id')
                    ->label('Patient ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('patient.full_name')
                    ->label('Patient')
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
                TableFilters::distinct('medicine_name', Pharmacy::class, 'Medicine'),
                TableFilters::distinct('supplier_id', Pharmacy::class, 'Supplier'),
                TableFilters::relationship('patient', 'full_name', 'Patient'),
                TableFilters::distinct('prescription_sale_id', Pharmacy::class, 'Prescription'),
                TableFilters::dateRange('expiry_date', 'Expiry Date'),
                TableFilters::dateRange('received_at', 'Received Date'),
                TableFilters::createdAt(),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                Action::make('dispense')
                    ->label('Dispense')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Pharmacy $record): bool => $record->stock_qty > 0)
                    ->form([
                        TextInput::make('quantity')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        Select::make('payment_status')
                            ->options([
                                'paid' => 'Paid',
                                'pending' => 'Pending',
                            ])
                            ->default('paid')
                            ->required(),
                        TextInput::make('prescription_id')
                            ->label('Prescription ID')
                            ->maxLength(50),
                        TextInput::make('patient_id')
                            ->label('Patient (ID or Code)')
                            ->numeric(),
                        Textarea::make('notes')
                            ->rows(2),
                    ])
                    ->action(function (Pharmacy $record, array $data): void {
                        $quantity = (int) ($data['quantity'] ?? 0);
                        $rawPatientInput = $data['patient_id'] ?? null;

                        if ($quantity < 1 || $quantity > $record->stock_qty) {
                            Notification::make()
                                ->title('Insufficient stock for dispense.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $resolvedPatientId = null;

                        if (filled($rawPatientInput)) {
                            $resolvedPatientId = Patients::query()
                                ->whereKey($rawPatientInput)
                                ->value('id')
                                ?? Patients::query()
                                    ->where('patient_id', (string) $rawPatientInput)
                                    ->value('id');

                            if (! $resolvedPatientId) {
                                Notification::make()
                                    ->title('Patient not found.')
                                    ->body('Use valid patients.id or patient code (patient_id).')
                                    ->danger()
                                    ->send();

                                return;
                            }
                        }

                        $inventoryItem = $record->inventoryItem;

                        if (! $inventoryItem) {
                            Notification::make()
                                ->title('Inventory item not found for this queue record.')
                                ->danger()
                                ->send();

                            return;
                        }

                        try {
                            InventoryService::dispensePrescription(
                                $inventoryItem,
                                $quantity,
                                (string) ($data['prescription_id'] ?: $record->prescription_sale_id ?: ('PHARM-' . $record->id)),
                                'Pharmacist dispensed medicine to patient'
                            );

                            $record->update([
                                'stock_qty' => $record->stock_qty - $quantity,
                                'prescription_sale_id' => $data['prescription_id'] ?: $record->prescription_sale_id,
                                'issued_to_patient_id' => $resolvedPatientId ?: $record->issued_to_patient_id,
                            ]);

                            $sale = PharmacySale::query()->create([
                                'sale_id' => 'PS-' . now()->format('YmdHis') . '-' . $record->id,
                                'sale_type' => 'prescription',
                                'pharmacy_id' => $record->id,
                                'inventory_item_id' => $record->medicine_id,
                                'prescription_id' => $data['prescription_id'] ?: $record->prescription_sale_id,
                                'patient_id' => $resolvedPatientId ?: $record->issued_to_patient_id,
                                'medicine_name' => $record->medicine_name,
                                'quantity' => $quantity,
                                'unit_price' => $record->unit_price,
                                'total_amount' => $record->unit_price * $quantity,
                                'payment_status' => $data['payment_status'] ?? 'paid',
                                'notes' => $data['notes'] ?? null,
                                'sold_by' => Auth::id(),
                                'sold_at' => now(),
                            ]);

                            PharmacyMovement::create([
                                'pharmacy_id' => $record->id,
                                'movement_type' => 'dispense',
                                'direction' => 'out',
                                'quantity' => $quantity,
                                'patient_id' => $resolvedPatientId ?: $record->issued_to_patient_id,
                                'prescription_id' => $data['prescription_id'] ?? $record->prescription_sale_id,
                                'reference' => $sale->sale_id,
                                'notes' => $data['notes'] ?? null,
                                'created_by' => Auth::id(),
                            ]);

                            if ($resolvedPatientId ?: $record->issued_to_patient_id) {
                                PatientHistory::create([
                                    'patient_id' => $resolvedPatientId ?: $record->issued_to_patient_id,
                                    'encounter_id' => $data['prescription_id'] ?: $record->prescription_sale_id,
                                    'source' => 'pharmacy',
                                    'activity' => 'Medication dispensed',
                                    'details' => "Medicine: {$record->medicine_name}, Qty: {$quantity}, Sale: {$sale->sale_id}",
                                    'occurred_at' => now(),
                                    'created_by' => Auth::id(),
                                ]);
                            }

                            Prescription::query()
                                ->where('prescription_id', $data['prescription_id'] ?: $record->prescription_sale_id)
                                ->update(['status' => 'dispensed']);

                            Notification::make()
                                ->title('Medicine dispensed and pharmacy sale recorded.')
                                ->body("Sale reference: {$sale->sale_id}")
                                ->success()
                                ->send();
                        } catch (Throwable $exception) {
                            Notification::make()
                                ->title('Dispense failed.')
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Action::make('receive')
                    ->label('Receive')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->visible(fn (Pharmacy $record): bool => blank($record->received_at))
                    ->form([
                        TextInput::make('quantity')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        TextInput::make('reference')
                            ->label('GRN / Invoice')
                            ->maxLength(120),
                        Textarea::make('notes')
                            ->rows(2),
                    ])
                    ->action(function (Pharmacy $record, array $data): void {
                        $quantity = (int) ($data['quantity'] ?? 0);
                        if ($quantity < 1) {
                            return;
                        }

                        $record->update([
                            'stock_qty' => $record->stock_qty + $quantity,
                            'received_at' => now(),
                        ]);

                        PharmacyMovement::create([
                            'pharmacy_id' => $record->id,
                            'movement_type' => 'receive',
                            'direction' => 'in',
                            'quantity' => $quantity,
                            'reference' => $data['reference'] ?? null,
                            'notes' => $data['notes'] ?? null,
                            'created_by' => Auth::id(),
                        ]);

                        if (filled($record->issued_to_patient_id)) {
                            PatientHistory::create([
                                'patient_id' => $record->issued_to_patient_id,
                                'encounter_id' => $record->prescription_sale_id,
                                'source' => 'pharmacy',
                                'activity' => 'Medication received',
                                'details' => "Medicine: {$record->medicine_name}, Qty: {$quantity}",
                                'occurred_at' => now(),
                                'created_by' => Auth::id(),
                            ]);
                        }

                        Notification::make()
                            ->title('Stock received successfully.')
                            ->success()
                            ->send();
                    }),
                Action::make('received')
                    ->label('Received')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->disabled()
                    ->visible(fn (Pharmacy $record): bool => filled($record->received_at))
                    ->action(fn (): null => null),
                Action::make('loss_adjustment')
                    ->label('Loss/Adjust')
                    ->icon('heroicon-o-scale')
                    ->color('warning')
                    ->form([
                        Select::make('adjustment_type')
                            ->options([
                                'increase' => 'Increase',
                                'decrease' => 'Decrease',
                            ])
                            ->required(),
                        TextInput::make('quantity')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        Textarea::make('notes')
                            ->label('Reason')
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function (Pharmacy $record, array $data): void {
                        $quantity = (int) ($data['quantity'] ?? 0);
                        $type = (string) ($data['adjustment_type'] ?? '');

                        if ($quantity < 1) {
                            return;
                        }

                        if ($type === 'decrease' && $quantity > $record->stock_qty) {
                            Notification::make()
                                ->title('Stock not enough for decrease adjustment.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $newQty = $type === 'increase'
                            ? $record->stock_qty + $quantity
                            : $record->stock_qty - $quantity;

                        $record->update([
                            'stock_qty' => $newQty,
                        ]);

                        PharmacyMovement::create([
                            'pharmacy_id' => $record->id,
                            'movement_type' => 'loss_adjustment',
                            'direction' => 'adjust',
                            'quantity' => $quantity,
                            'notes' => $data['notes'] ?? null,
                            'created_by' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Stock adjustment saved.')
                            ->success()
                            ->send();
                    }),
                Action::make('transfer')
                    ->label('Transfer')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('gray')
                    ->form([
                        TextInput::make('quantity')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        TextInput::make('reference')
                            ->label('To Store / Branch')
                            ->required()
                            ->maxLength(120),
                        Textarea::make('notes')
                            ->rows(2),
                    ])
                    ->action(function (Pharmacy $record, array $data): void {
                        $quantity = (int) ($data['quantity'] ?? 0);
                        if ($quantity < 1 || $quantity > $record->stock_qty) {
                            Notification::make()
                                ->title('Stock not enough for transfer.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->update([
                            'stock_qty' => $record->stock_qty - $quantity,
                        ]);

                        PharmacyMovement::create([
                            'pharmacy_id' => $record->id,
                            'movement_type' => 'transfer',
                            'direction' => 'out',
                            'quantity' => $quantity,
                            'reference' => $data['reference'] ?? null,
                            'notes' => $data['notes'] ?? null,
                            'created_by' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Stock transferred.')
                            ->success()
                            ->send();
                    }),
                Action::make('disposal')
                    ->label('Disposal')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->form([
                        TextInput::make('quantity')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        Textarea::make('notes')
                            ->label('Disposal reason')
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function (Pharmacy $record, array $data): void {
                        $quantity = (int) ($data['quantity'] ?? 0);
                        if ($quantity < 1 || $quantity > $record->stock_qty) {
                            Notification::make()
                                ->title('Stock not enough for disposal.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->update([
                            'stock_qty' => $record->stock_qty - $quantity,
                        ]);

                        PharmacyMovement::create([
                            'pharmacy_id' => $record->id,
                            'movement_type' => 'disposal',
                            'direction' => 'out',
                            'quantity' => $quantity,
                            'notes' => $data['notes'] ?? null,
                            'created_by' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Stock disposed.')
                            ->success()
                            ->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
