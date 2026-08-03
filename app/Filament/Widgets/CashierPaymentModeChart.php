<?php

namespace App\Filament\Widgets;

use App\Models\Payments;
use Filament\Widgets\ChartWidget;

class CashierPaymentModeChart extends ChartWidget
{
    protected ?string $heading = 'Today Payment Modes';

    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $totals = Payments::query()
            ->whereDate('payment_date', today())
            ->selectRaw('payment_mode, SUM(amount) as total')
            ->groupBy('payment_mode')
            ->pluck('total', 'payment_mode')
            ->toArray();

        $modes = ['cash', 'card', 'upi', 'netbanking', 'insurance', 'mixed'];

        return [
            'datasets' => [
                [
                    'label' => 'ETB',
                    'data' => array_map(static fn (string $mode): float => (float) ($totals[$mode] ?? 0), $modes),
                    'backgroundColor' => ['#059669', '#2563EB', '#7C3AED', '#0F766E', '#F59E0B', '#64748B'],
                ],
            ],
            'labels' => ['Cash', 'Card', 'UPI', 'Net banking', 'Insurance', 'Mixed'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
