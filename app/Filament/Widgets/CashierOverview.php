<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ServiceRequests\ServiceRequestResource;
use App\Models\Payments;
use App\Models\ServiceRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CashierOverview extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $pending = ServiceRequest::query()
            ->whereIn('payment_status', ['pending', 'verified'])
            ->count();
        $urgent = ServiceRequest::query()
            ->where('payment_status', 'pending')
            ->where('requested_at', '<', now()->subMinutes(30))
            ->count();
        $paidToday = ServiceRequest::query()
            ->whereDate('paid_at', today())
            ->where('payment_status', 'paid')
            ->count();
        $collectedToday = (float) Payments::query()
            ->whereDate('payment_date', today())
            ->whereIn('payment_mode', ['cash', 'card', 'upi', 'netbanking', 'mixed'])
            ->sum('amount');
        $insuranceToday = (float) Payments::query()
            ->whereDate('payment_date', today())
            ->where('payment_mode', 'insurance')
            ->sum('amount');

        return [
            Stat::make('Pending Payments', (string) $pending)
                ->description('Waiting at cashier')
                ->icon('heroicon-o-clock')
                ->url(ServiceRequestResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#D97706')),
            Stat::make('Urgent Queue', (string) $urgent)
                ->description('Pending over 30 minutes')
                ->icon('heroicon-o-exclamation-triangle')
                ->url(ServiceRequestResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#B91C1C')),
            Stat::make('Paid Today', (string) $paidToday)
                ->description('Completed service payments')
                ->icon('heroicon-o-check-circle')
                ->url(ServiceRequestResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#059669')),
            Stat::make('Collected Today', 'ETB ' . number_format($collectedToday, 2))
                ->description('Cash/card/mobile total')
                ->icon('heroicon-o-banknotes')
                ->url(ServiceRequestResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#0F766E')),
            Stat::make('Insurance Today', 'ETB ' . number_format($insuranceToday, 2))
                ->description('Approved insurance amount')
                ->icon('heroicon-o-shield-check')
                ->url(ServiceRequestResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#1D4ED8')),
        ];
    }

    protected function getColumns(): int
    {
        return 5;
    }

    /**
     * @return array<string, string>
     */
    private function cardStyle(string $background, string $textColor = '#ffffff'): array
    {
        return [
            'style' => "background: {$background}; color: {$textColor}; border-radius: 0.6rem;",
            'class' => 'shadow-sm',
        ];
    }
}
