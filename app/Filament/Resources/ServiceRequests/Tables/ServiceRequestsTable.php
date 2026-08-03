<?php

namespace App\Filament\Resources\ServiceRequests\Tables;

use App\Filament\Support\TableFilters;
use App\Models\Payments;
use App\Models\ServiceRequest;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ServiceRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('request_number')
                    ->label('Request')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('patient.full_name')
                    ->label('PatientName')
                    ->searchable()
                    ->description(fn (ServiceRequest $record): string => $record->patient?->full_name ?? ''),
                TextColumn::make('encounter_id')
                    ->label('Encounter')
                    ->searchable()
                    ->description(fn (ServiceRequest $record): string => $record->encounter_type ?? ''),
                TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable()
                    ->description(fn (ServiceRequest $record): string => str($record->service?->service_type ?? '')->title()->toString()),
                TextColumn::make('patient_share')
                    ->label('Patient Share')
                    ->money('ETB')
                    ->sortable(),
                TextColumn::make('insurance_share')
                    ->label('Insurance')
                    ->money('ETB')
                    ->sortable(),
                TextColumn::make('paid_amount')
                    ->label('Paid')
                    ->money('ETB')
                    ->sortable(),
                TextColumn::make('balance')
                    ->label('Balance')
                    ->money('ETB'),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid', 'insurance', 'waived' => 'success',
                        'verified' => 'info',
                        'cancelled' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('fulfillment_status')
                    ->badge(),
                TextColumn::make('requested_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TableFilters::select('payment_status', [
                    'pending' => 'Pending',
                    'verified' => 'Partially verified',
                    'paid' => 'Paid',
                    'insurance' => 'Insurance',
                    'waived' => 'Waived',
                    'cancelled' => 'Cancelled',
                ]),
                TableFilters::select('fulfillment_status', [
                    'requested' => 'Requested',
                    'in_progress' => 'In progress',
                    'completed' => 'Completed',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                ]),
                TableFilters::relationship('patient', 'full_name', 'Patient'),
                TableFilters::relationship('service', 'name', 'Service'),
                TableFilters::createdAt('requested_at', 'Requested Date'),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->defaultSort('requested_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->with(['patient', 'service', 'payment']))
            ->recordActions([
                Action::make('collect_payment')
                    ->label('Collect Payment')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (ServiceRequest $record): bool => ! in_array($record->payment_status, ['paid', 'insurance', 'waived', 'cancelled'], true))
                    ->form([
                        TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->default(fn (ServiceRequest $record): float => max(0, (float) $record->patient_share - (float) $record->discount - (float) $record->paid_amount)),
                        Select::make('payment_mode')
                            ->options([
                                'cash' => 'Cash',
                                'card' => 'Card',
                                'upi' => 'UPI',
                                'netbanking' => 'Net banking',
                                'mixed' => 'Mixed',
                            ])
                            ->default('cash')
                            ->required(),
                        TextInput::make('transaction_id')
                            ->maxLength(120),
                        Textarea::make('remarks')
                            ->rows(3),
                    ])
                    ->action(function (ServiceRequest $record, array $data): void {
                        $amount = (float) ($data['amount'] ?? 0);

                        if ($amount <= 0) {
                            Notification::make()
                                ->title('Payment amount must be greater than zero.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $payment = $record->markAsPaid(Auth::id(), $amount, $data['payment_mode']);
                        $payment->update([
                            'transaction_id' => $data['transaction_id'] ?? null,
                            'remarks' => $data['remarks'] ?? null,
                        ]);

                        Notification::make()
                            ->title('Payment collected. Department can proceed.')
                            ->body("Invoice: {$payment->invoice_number}")
                            ->success()
                            ->send();
                    }),
                Action::make('approve_insurance')
                    ->label('Approve Insurance')
                    ->icon('heroicon-o-shield-check')
                    ->color('info')
                    ->visible(fn (ServiceRequest $record): bool => $record->insurance_share > 0 && ! $record->canProceedForService())
                    ->requiresConfirmation()
                    ->action(function (ServiceRequest $record): void {
                        $record->update([
                            'payment_status' => 'insurance',
                            'verified_by' => Auth::id(),
                            'verified_at' => now(),
                            'fulfillment_status' => 'in_progress',
                        ]);

                        Payments::create([
                            'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . $record->id . '-' . random_int(100, 999),
                            'service_request_id' => $record->id,
                            'patient_id' => $record->patient_id,
                            'collected_by' => Auth::id(),
                            'payment_mode' => 'insurance',
                            'amount' => $record->insurance_share,
                            'remarks' => 'Insurance approved for service request.',
                            'payment_date' => now(),
                        ]);

                        Notification::make()
                            ->title('Insurance approved. Department can proceed.')
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
