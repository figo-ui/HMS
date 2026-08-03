<?php

namespace App\Filament\Widgets;

use App\Models\Patients;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Widgets\ChartWidget;

class HospitalIncomeExpenseChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Patient Count';

    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $patientsByMonth = Patients::query()
            ->selectRaw('MONTH(COALESCE(registered_at, created_at)) as month_no, COUNT(*) as total')
            ->groupBy('month_no')
            ->pluck('total', 'month_no')
            ->toArray();

        $counts = [];

        for ($month = 1; $month <= 12; $month++) {
            $counts[] = (int) ($patientsByMonth[$month] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Patients',
                    'data' => $counts,
                    'backgroundColor' => '#22C55E',
                    'borderColor' => '#16A34A',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
