<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
//use Filament\Forms\Components\Group;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Get;
use Filament\Forms\Set;

use App\Models\ServiceRequest;
use App\Models\Patients;

class PaymentsForm
{
    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                 Section::make('💰 Payment Information')
            ->description('Collect payment from patient or insurance')
            ->icon('heroicon-o-currency-dollar')
           // ->schema(self::getPaymentFields())
            ->columns(2),
            
             Section::make('🔄 Mixed Payment Details')
            ->description('Split payment between multiple methods')
            ->icon('heroicon-o-calculator')
           // ->schema(self::getMixedPaymentFields())
            ->collapsible()
            ->live()
   ->collapsed(fn($get) => $get('payment_mode') !== 'mixed')
    ->visible(fn($get) => $get('payment_mode') === 'mixed'),
     Section::make('🔐 Transaction Details')
            ->description('For card and online payments')
            ->icon('heroicon-o-lock-closed')
           // ->schema(self::getTransactionFields())
            ->collapsible(),
            Select::make('service_request_id')
                ->label('Service Request')
                ->relationship(
                    name: 'serviceRequest',
                    titleAttribute: 'request_number',
                    modifyQueryUsing: fn ($query) => $query->where('payment_status', 'verified')
                )
                ->searchable()
                ->preload()
                ->required()
                ->live()
               ->afterStateUpdated(function ($state, $set, $get) {
                    if ($state) {
                        $serviceRequest = ServiceRequest::with(['patient', 'service'])->find($state);
                        if ($serviceRequest) {
                            $set('patient_id', $serviceRequest->patient_id);
                            $set('amount', $serviceRequest->patient_share);
                            $set('invoice_number', 'INV-' . now()->format('YmdHis') . '-' . $serviceRequest->id);
                            $set('due_amount', $serviceRequest->patient_share);
                        }
                    }
                })
            
                ->helperText('Select the verified service request to process payment'),

            Hidden::make('patient_id'),

            Placeholder::make('patient_name')
                ->label('Patient Name')
                ->content(function ($get) {
                    $patientId = $get('patient_id');
                    if ($patientId) {
                        $patient = Patients::find($patientId);
                        return $patient ? $patient->name : '-';
                    }
                    return '-';
                }),

            Placeholder::make('service_name')
                ->label('Service')
                ->content(function ($get) {
                    $requestId = $get('service_request_id');
                    if ($requestId) {
                        $request = ServiceRequest::with('service')->find($requestId);
                        return $request ? $request->service->name : '-';
                    }
                    return '-';
                }),

            TextInput::make('invoice_number')
                ->label('Invoice Number')
                ->required()
                ->unique(ignoreRecord: true)
                ->default(fn () => 'INV-' . now()->format('YmdHis') . '-' . rand(100, 999))
                ->disabled()
                ->dehydrated()
                ->prefix('📄'),

            Select::make('payment_mode')
                ->label('Payment Mode')
                ->options([
                    'cash' => '💵 Cash',
                    'card' => '💳 Credit/Debit Card',
                    'upi' => '📱 UPI / Mobile Payment',
                    'netbanking' => '🏦 Net Banking',
                    'insurance' => '🏥 Insurance Claim',
                    'mixed' => '🔄 Mixed (Cash + Card)',
                ])
                ->required()
                ->live()
                ->native(false)
                ->afterStateUpdated(function ($state, $set) {
                    if ($state !== 'mixed') {
                        $set('split_details', null);
                    }
                }),

            TextInput::make('amount')
                ->label('Payment Amount')
                ->prefix('$')
                ->numeric()
                ->required()
                ->minValue(1)
                ->maxValue(100000)
                ->helperText('Enter the amount being collected')
                ->live()
                 ->afterStateUpdated(function ($state, $get, $set) {
                    $due = $get('due_amount');
                    if ($state > $due) {
                        $set('amount', $due);
                    }
                }),

           Placeholder::make('due_amount')
                ->label('Amount Due')
                ->content(function ($get) {
                    $due = $get('due_amount');
                    $amount = $get('amount');
                    if ($due && $amount) {
                        $remaining = $due - $amount;
                        if ($remaining > 0) {
                            return "<span class='text-warning-600'>\$" . number_format($remaining, 2) . " remaining</span>";
                        } elseif ($remaining == 0) {
                            return "<span class='text-success-600'>✓ Fully paid</span>";
                        }
                    }
                    return '$' . number_format($due ?? 0, 2);
                }),
        

                    TextInput::make('split_details.cash')
                        ->label('Cash Amount')
                        ->prefix('$')
                        ->numeric()
                        ->live()
                    ->afterStateUpdated(function ($state, $get, $set) {
                            $this->updateSplitTotal($get, $set);
                        }),

                    TextInput::make('split_details.card')
                        ->label('Card Amount')
                        ->prefix('$')
                        ->numeric()
                        ->live()
                        ->afterStateUpdated(fn ( $get,  $set) => self::updateSplitTotal($get, $set)),

                    TextInput::make('split_details.upi')
                        ->label('UPI Amount')
                        ->prefix('$')
                        ->numeric()
                        ->live()
                       ->afterStateUpdated(function ($state, $get, $set) {
                            $this->updateSplitTotal($get, $set);
                        }),

                    TextInput::make('split_details.insurance')
                        ->label('Insurance Amount')
                        ->prefix('$')
                        ->numeric()
                        ->live()
                       ->afterStateUpdated(function ($state, $get, $set) {
                            $this->updateSplitTotal($get, $set);
              }),
                

            Placeholder::make('split_total')
                ->label('Total')
                ->live()
                 ->content(function ($get) {
                    $total = 0;
                    $split = $get('split_details') ?? [];
                    foreach ($split as $amount) {
                        $total += floatval($amount);
                    }
                    return "<strong>\$" . number_format($total, 2) . "</strong>";
                }),
            TextInput::make('transaction_id')
                ->label('Transaction ID / Reference Number')
                ->placeholder('e.g., TXN123456789')
                ->helperText('Required for card/online payments')
                ->maxLength(100),

            DateTimePicker::make('payment_date')
                ->label('Payment Date & Time')
                ->required()
                ->default(now())
                ->seconds(false),

            Textarea::make('remarks')
                ->label('Remarks / Notes')
                ->placeholder('Any additional notes about this payment...')
                ->maxLength(500)
                ->columnSpanFull(),
          Section::make('📋 Payment Summary')
            ->icon('heroicon-o-document-text')
           // ->schema(self::getSummaryFields())
            ,
 Section::make('👤 Cashier Information')
            ->icon('heroicon-o-user')
            //->schema(self::getCashierFields())
            ,

 Placeholder::make('summary_status')
                ->label('Status')
                ->live()
               ->content(function ($get) {
                    $amount = $get('amount');
                    $due = $get('due_amount');
                    if (!$amount || !$due) return 'Pending';
                    return $amount >= $due ? 'Full Payment' : 'Partial Payment';
                }),

            Placeholder::make('summary_due')
                ->label('Original Due')
               ->content(fn($get) => '$' . number_format($get('due_amount') ?? 0, 2)),
 Placeholder::make('summary_paid')
                ->label('Paying Now')
                ->content(fn($get) => '$' . number_format($get('amount') ?? 0, 2)),
            
            Placeholder::make('summary_remaining')
                ->label('Remaining Balance')
                ->content(function ($get) {
                    $due = $get('due_amount') ?? 0;
                    $amount = $get('amount') ?? 0;
                    $remaining = $due - $amount;
                    $color = $remaining > 0 ? 'text-danger-600' : 'text-success-600';
                    return "<span class='{$color}'>\$" . number_format($remaining, 2) . "</span>";
                }),
            Placeholder::make('cashier_name')
                ->label('Cashier')
                ->content(auth()->user()?->name ?? 'System'),

            Hidden::make('collected_by')
                ->default(auth()->id()),

            Placeholder::make('payment_time')
                ->label('Time')
                ->content(now()->format('F j, Y g:i A')),
        
            ]);
    }


   private  static function updateSplitTotal( $get,  $set): void
    {
        $split = $get('split_details') ?? [];
        // Ensure all values are numeric
        $total = array_sum(array_map(function ($value) {
            return is_numeric($value) ? floatval($value) : 0;
        }, $split));
        
        $set('amount', $total);
    }
    
}
