<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\Payments;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\Layout\SplitColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Columns;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\StackItem;
use Filament\Tables\Columns\Layout\ColumnsItem;
use Filament\Tables\Columns\Layout\GridItem;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;



class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                 TextColumn::make('invoice_number')
                ->label('Invoice #')
                ->searchable()
                ->copyable()
                ->copyMessage('Invoice number copied')
                ->icon('heroicon-o-document-duplicate')
                ->color('primary')
                ->weight('bold')
                ->sortable(),
            
            TextColumn::make('patient.full_name')
                ->label('Patient')
                ->searchable()
                ->sortable()
                ->icon('heroicon-o-user')
                ->description(fn(Payments $record): string => $record->patient->full_name ?? ''),
            
            TextColumn::make('serviceRequest.service.name')
                ->label('Service')
                ->limit(30)
                ->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();
                    return strlen($state) > 30 ? $state : null;
                }),
            
            TextColumn::make('payment_mode')
                ->label('Payment Mode')
                ->badge()
                ->icon(function (string $state): string {
                    return match($state) {
                        'cash' => 'heroicon-o-banknotes',
                        'card' => 'heroicon-o-credit-card',
                        'upi' => 'heroicon-o-device-phone-mobile',
                        'netbanking' => 'heroicon-o-globe-alt',
                        'insurance' => 'heroicon-o-shield-check',
                        'mixed' => 'heroicon-o-arrows-right-left',
                        default => 'heroicon-o-currency-dollar',
                    };
                })
                ->color(fn(string $state): string => match($state) {
                    'cash' => 'success',
                    'card' => 'info',
                    'upi' => 'warning',
                    'netbanking' => 'primary',
                    'insurance' => 'secondary',
                    'mixed' => 'gray',
                    default => 'gray',
                })
                ->formatStateUsing(fn(string $state): string => ucfirst($state)),
            
            TextColumn::make('amount')
                ->label('Amount')
                ->money('USD')
                ->sortable()
                ->color('success')
                ->weight('bold'),
            
            TextColumn::make('payment_date')
                ->label('Payment Date')
                ->dateTime('M d, Y H:i')
                ->sortable()
                ->icon('heroicon-o-calendar')
                ->since(),
                //->tooltip(fn(Payments $record): string => $record->payment_date->format('F j, Y g:i A')),
            
            TextColumn::make('collector.name')
                ->label('Cashier')
                ->searchable()
                ->icon('heroicon-o-user-circle')
                ->toggleable(isToggledHiddenByDefault: false),
            
            TextColumn::make('transaction_id')
                ->label('Transaction ID')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->copyable()
                ->limit(15),
            
            TextColumn::make('serviceRequest.payment_status')
                ->label('Status')
                ->badge()
                ->color(fn(string $state): string => match($state) {
                    'pending' => 'danger',
                    'verified' => 'warning',
                    'paid' => 'success',
                    'insurance' => 'info',
                    default => 'gray',
                })
                ->icon(fn(string $state): string => match($state) {
                    'pending' => 'heroicon-o-clock',
                    'verified' => 'heroicon-o-check-badge',
                    'paid' => 'heroicon-o-check-circle',
                    'insurance' => 'heroicon-o-building-library',
                    default => 'heroicon-o-question-mark-circle',
                }),
            
            TextColumn::make('created_at')
                ->label('Recorded')
                ->dateTime('M d, Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        
                //
            ])
            ->filters([

             SelectFilter::make('payment_mode')
                ->label('Payment Mode')
                ->options([
                    'cash' => '💵 Cash',
                    'card' => '💳 Card',
                    'upi' => '📱 UPI',
                    'netbanking' => '🏦 Net Banking',
                    'insurance' => '🏥 Insurance',
                    'mixed' => '🔄 Mixed',
                ])
                ->multiple()
                ->placeholder('All Modes'),
            

            
            Filter::make('today')
                ->label('Today')
                ->query(fn(Builder $query): Builder => $query->whereDate('payment_date', today()))
                ->default(false),
            
            Filter::make('this_week')
                ->label('This Week')
                ->query(fn(Builder $query): Builder => $query->whereBetween('payment_date', [now()->startOfWeek(), now()->endOfWeek()])),
            
            Filter::make('this_month')
                ->label('This Month')
                ->query(fn(Builder $query): Builder => $query->whereMonth('payment_date', now()->month)),
        
                //
            ])
            ->recordActions([
                Action::make('view')
                ->label('View')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->modalHeading('Payment Details')
                ->modalContent(function (Payments $record) {
                    return view('filament.view-payment', ['payments' => $record]);
                })
                ->modalWidth('2xl')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close'),
            
           EditAction::make()
                ->color('primary'),
            
           DeleteAction::make()
                ->color('danger'),
            ])
            ->toolbarActions([
                 BulkActionGroup::make([
             DeleteBulkAction::make()
                    ->modalHeading('Delete Selected Payments')
                    ->modalDescription('Are you sure you want to delete the selected payments?'),
                
                BulkAction::make('export')
                    ->label('Export Selected')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function ($records) {
                        \Filament\Notifications\Notification::make()
                            ->title('Export Started')
                            ->body(count($records) . ' records will be exported')
                            ->info()
                            ->send();
       })
          ])
                       ]);
                               
            
    }
}
