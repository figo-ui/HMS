<?php

namespace App\Filament\Widgets;

use App\Models\Appointments;
use App\Models\Patients;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RolePatientAppointmentsTrendChart extends ChartWidget
{
    protected ?string $heading = 'My 6-Month Appointment Trend';

    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $user = auth()->user();
        $patientId = $user
            ? Patients::query()->where('user_id', $user->id)->value('id')
            : null;

        $start = Carbon::today()->startOfMonth()->subMonths(5);

        $monthlyCounts = $patientId
            ? Appointments::query()
                ->selectRaw("DATE_FORMAT(`date`, '%Y-%m') as ym, COUNT(*) as total")
                ->where('patient_id', $patientId)
                ->whereDate('date', '>=', $start->toDateString())
                ->groupBy('ym')
                ->pluck('total', 'ym')
                ->toArray()
            : [];

        $labels = [];
        $data = [];
        for ($i = 0; $i < 6; $i++) {
            $month = $start->copy()->addMonths($i);
            $key = $month->format('Y-m');
            $labels[] = $month->format('M Y');
            $data[] = (int) ($monthlyCounts[$key] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'My appointments',
                    'data' => $data,
                    'backgroundColor' => 'rgba(14, 165, 233, 0.20)',
                    'borderColor' => '#0284C7',
                    'tension' => 0.35,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
