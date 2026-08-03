<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ServiceRequests\ServiceRequestResource;
use App\Models\ServiceRequest;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class CashierQueue extends TableWidget
{
    protected int|string|array $columnSpan = 2;

    protected ?string $pollingInterval = '10s';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Live Cashier Queue')
            ->query(fn (): Builder => ServiceRequest::query()
                ->with(['patient', 'service'])
                ->whereIn('payment_status', ['pending', 'verified'])
                ->orderBy('requested_at'))
            ->columns([
                TextColumn::make('request_number')
                    ->label('Request')
                    ->searchable(),
                TextColumn::make('patient.full_name')
                    ->label('Patient')
                    ->searchable(),
                TextColumn::make('service.name')
                    ->label('Service')
                    ->description(fn (ServiceRequest $record): string => str($record->service?->service_type ?? '')->title()->toString()),
                TextColumn::make('patient_share')
                    ->label('Patient Share')
                    ->money('ETB'),
                TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'verified' ? 'info' : 'warning'),
                TextColumn::make('requested_at')
                    ->label('Requested')
                    ->since(),
            ])
            ->recordUrl(fn (ServiceRequest $record): string => ServiceRequestResource::getUrl('edit', ['record' => $record]));
    }
}
