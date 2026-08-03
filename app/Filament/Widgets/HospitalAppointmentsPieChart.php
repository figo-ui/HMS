<?php

namespace App\Filament\Widgets;

use App\Models\Appointments;
use Filament\Widgets\ChartWidget;
use Spatie\Permission\Commands\Show;

class HospitalAppointmentsPieChart extends ChartWidget
{
    protected ?string $heading = 'Appointment Status';

    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $statusCounts = Appointments::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $labels = ['scheduled', 'completed', 'cancelled', 'no_show'];
        $colors = ['#3B82F6', '#10B981', '#EF4444', '#F59E0B'];

        $data = array_map(static fn ($status) => (int) ($statusCounts[$status] ?? 0), $labels);

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => ['Scheduled', 'Completed', 'Cancelled', 'No Show'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
