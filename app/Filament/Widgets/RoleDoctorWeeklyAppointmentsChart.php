<?php

namespace App\Filament\Widgets;

use App\Models\Appointments;
use App\Models\Doctors;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class RoleDoctorWeeklyAppointmentsChart extends ChartWidget
{
    protected ?string $heading = 'Weekly Appointment Load';

    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $doctorIds = $this->resolveCurrentDoctorIds();

        $start = Carbon::today()->subDays(6);
        $end = Carbon::today();

        $query = Appointments::query()
            ->selectRaw('DATE(`date`) as appt_day, COUNT(*) as total')
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->groupBy('appt_day')
            ->pluck('total', 'appt_day')
            ->toArray();

        if ($doctorIds !== []) {
            $query = Appointments::query()
                ->selectRaw('DATE(`date`) as appt_day, COUNT(*) as total')
                ->whereIn('doctor_id', $doctorIds)
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->groupBy('appt_day')
                ->pluck('total', 'appt_day')
                ->toArray();
        }

        $labels = [];
        $data = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $start->copy()->addDays($i);
            $key = $day->toDateString();
            $labels[] = $day->format('D');
            $data[] = (int) ($query[$key] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Appointments',
                    'data' => $data,
                    'backgroundColor' => '#2563EB',
                    'borderColor' => '#1D4ED8',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * @return array<int>
     */
    protected function resolveCurrentDoctorIds(): array
    {
        $user = auth()->user();

        if (! $user) {
            return [];
        }

        $query = Doctors::query();

        if (Schema::hasColumn('doctors', 'user_id')) {
            $ids = (clone $query)->where('user_id', $user->id)->pluck('id')->all();
            if ($ids !== []) {
                return $ids;
            }
        }

        return (clone $query)
            ->where('email', $user->email)
            ->pluck('id')
            ->all();
    }
}
