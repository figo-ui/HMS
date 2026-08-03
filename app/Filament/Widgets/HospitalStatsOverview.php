<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Appointments\AppointmentsResource;
use App\Filament\Resources\Doctors\DoctorsResource;
use App\Filament\Resources\Laboratories\LaboratoryResource;
use App\Filament\Resources\Patients\PatientsResource;
use App\Filament\Resources\Prescriptions\PrescriptionResource;
use App\Filament\Resources\Staff\StaffResource;
use App\Models\Appointments;
use App\Models\Doctors;
use App\Models\Laboratory;
use App\Models\Patients;
use App\Models\Prescription;
use App\Models\Staff;
use Filament\Auth\Events\Registered;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use SebastianBergmann\CodeCoverage\Report\Xml\Tests;

class HospitalStatsOverview extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Doctors', (string) Doctors::count())
                ->description('Registered doctors')
                ->icon('heroicon-o-user-group')
                 ->color('#111111')
                ->url(DoctorsResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#17a2b8')),

            Stat::make('Patients', (string) Patients::count())
                ->description('Total patients')
                ->icon('heroicon-o-users')
                 ->color('#111111')
                ->url(PatientsResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#829e88')),

            Stat::make('Appointments', (string) Appointments::count())
                ->description('All appointments')
                ->icon('heroicon-o-calendar-days')
                 ->color('#111111')
                ->url(AppointmentsResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#ffc107', '#1f2937')),

            Stat::make('Prescriptions', (string) Prescription::count())
                ->description('Issued prescriptions')
                ->icon('heroicon-o-document-text')
                 ->color('#111111')
                ->url(PrescriptionResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#0deafa')),

            Stat::make('Lab Tests', (string) Laboratory::count())
                ->description('Laboratory records')
                ->icon('heroicon-o-beaker')
                ->color('#111111')
                ->url(LaboratoryResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#0ea5e9')),

            Stat::make('Staff', (string) Staff::count())
                ->description('HR staff count')
                ->icon('heroicon-o-identification')
                 ->color('#111111')
                ->url(StaffResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#0b5af8')),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }

    /**
     * @return array<string, string>
     */
    private function cardStyle(string $background, string $textColor = '#080808'): array
    {
        return [
            'style' => "background: {$background}; color: {$textColor}; border-radius: 0.5rem;",
            'class' => 'shadow-sm',
        ];
    }
}
