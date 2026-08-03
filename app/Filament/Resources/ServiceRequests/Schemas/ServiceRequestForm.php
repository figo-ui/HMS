<?php

namespace App\Filament\Resources\ServiceRequests\Schemas;

use Filament\Schemas\Schema;

use App\Filament\Resources\ServiceRequestResource\Pages;
use App\Models\ServiceRequest;
use App\Models\Patients;
use App\Models\Service;
use Filament\Forms;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
class ServiceRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
    
                       Section::make('Request Information')
                            ->schema([
                                Forms\Components\Select::make('patient_id')
                                    ->label('PatientName')
                                    ->relationship('patient', 'full_name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state,  $set) {
                                        if ($state) {
                                            $patient = Patients::find($state);
                                            $set('visit_id', $patient->current_visit_id);
                                        }
                                    }),
                                
                                Forms\Components\Select::make('service_id')
                                    ->label('Service')
                                    ->relationship('service', 'service_type')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $get , $set) {
                                        if ($state && $get('full_name')) {
                                            $service = Service::find($state);
                                           $patient = Patients::find($get('full_name'));
                                            
                                            $set('total_amount', $service->price);
                                            $set('patient_share', $service->calculatePatientShare($patient));
                                            $set('insurance_share', $service->calculateInsuranceShare($patient));
                                        }
                                    }),
                                
                                Forms\Components\Hidden::make('request_number')
                                    ->default(fn() => 'REQ-' . date('Ymd') . '-' . rand(1000, 9999)),
                                
                                Forms\Components\Hidden::make('requested_by')
                                    ->default(auth()->id()),
                                
                                Forms\Components\Hidden::make('requested_at')
                                    ->default(now()),
                            ])->columns(2),
          Section::make('Financial Details')
                            ->schema([
                                Forms\Components\TextInput::make('total_amount')
                                    ->label('Total Amount')
                                    ->prefix('$')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),
                                
                                Forms\Components\TextInput::make('patient_share')
                                    ->label('Patient Pays')
                                    ->prefix('$')
                                    ->numeric()
                                    ->required(),
                                
                                Forms\Components\TextInput::make('insurance_share')
                                    ->label('Insurance Covers')
                                    ->prefix('$')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),
                                
                                Forms\Components\TextInput::make('discount')
                                    ->label('Discount')
                                    ->prefix('$')
                                    ->numeric()
                                    ->default(0),
                            ])->columns(2),
                    ]);
          
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('Request #')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Request number copied'),
                
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('service.service_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn(string $state): string => match($state) {
                        'lab' => 'info',
                        'radiology' => 'warning',
                        'pharmacy' => 'success',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('patient_share')
                    ->label('Due')
                    ->money('USD')
                    ->color('danger')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'danger' => 'pending',
                        'warning' => 'verified',
                        'success' => 'paid',
                        'info' => 'insurance',
                    ]),
                
                Tables\Columns\TextColumn::make('requested_at')
                    ->label('Requested')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('requested_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'paid' => 'Paid',
                        'insurance' => 'Insurance',
                    ]),
                
                Tables\Filters\SelectFilter::make('service.service_type')
                    ->label('Service Type')
                    ->options([
                        'lab' => 'Lab',
                        'radiology' => 'Radiology',
                        'pharmacy' => 'Pharmacy',
                    ]),
                
                Tables\Filters\Filter::make('today')
                    ->label('Today')
                    ->query(fn($query) => $query->whereDate('requested_at', today())),
            ])
            ->actions([
                Actions::make('verify')
                    ->label('Verify Payment')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->payment_status === 'pending')
                    ->action(function ($record) {
                        $record->markAsVerified(auth()->id());
                        Notification::make()
                            ->title('Payment Verified')
                            ->body('The service request has been verified and can now proceed.')
                            ->success()
                            ->send();
                    }),
                
                Action::make('process')
                    ->label('Process Payment')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('primary')
                    ->visible(fn($record) => $record->payment_status === 'verified')
                    ->url(fn($record) => route('filament.resources.payments.create', ['service_request_id' => $record->id]))
                    ->openUrlInNewTab(),
                
               EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
               DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    }

