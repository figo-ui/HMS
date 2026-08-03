<?php

namespace App\Filament\Widgets;

use App\Models\Reports;
use Filament\Forms\Components\MorphToSelect\Type;
use Filament\Widgets\ChartWidget;

class HospitalReportsTypeChart extends ChartWidget
{
    protected ?string $heading = 'Reports by Type';

    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $typeCounts = Reports::query()
            ->selectRaw('COALESCE(report_type, "Unknown") as type_name, COUNT(*) as total')
            ->groupBy('type_name')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'type_name')
            ->toArray();

        if ($typeCounts === []) {
            return [
                'datasets' => [
                    [
                        'data' => [1],
                        'backgroundColor' => ['#9CA3AF'],
                    ],
                ],
                'labels' => ['No Reports'],
            ];
        }

        return [
            'datasets' => [
                [
                    'data' => array_values($typeCounts),
                    'backgroundColor' => ['#06B6D4', '#3B82F6', '#8B5CF6', '#F59E0B', '#EF4444', '#10B981'],
                ],
            ],
            'labels' => array_keys($typeCounts),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
