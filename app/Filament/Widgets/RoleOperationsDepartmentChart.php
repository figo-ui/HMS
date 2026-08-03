<?php

namespace App\Filament\Widgets;

use App\Models\IPD;
use App\Models\Laboratory;
use App\Models\OPD;
use App\Models\Radiology;
use Filament\Widgets\ChartWidget;

class RoleOperationsDepartmentChart extends ChartWidget
{
    protected ?string $heading = 'Department Queue Mix';

    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $data = [
            OPD::query()->count(),
            IPD::query()->count(),
            Laboratory::query()->count(),
            Radiology::query()->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Records',
                    'data' => $data,
                    'backgroundColor' => ['#1D4ED8', '#7C3AED', '#0F766E', '#B45309'],
                    'borderColor' => ['#1E40AF', '#6D28D9', '#115E59', '#92400E'],
                ],
            ],
            'labels' => ['OPD', 'IPD', 'Laboratory', 'Radiology'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
