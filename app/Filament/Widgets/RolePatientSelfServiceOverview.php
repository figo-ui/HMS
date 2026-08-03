<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Appointments\AppointmentsResource;
use App\Filament\Resources\BillingPayments\BillingPaymentResource;
use App\Filament\Resources\Prescriptions\PrescriptionResource;
use App\Models\Appointments;
use App\Models\BillingPayment;
use App\Models\Patients;
use App\Models\Prescription;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RolePatientSelfServiceOverview extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $user = auth()->user();
        $patientId = $user
            ? Patients::query()->where('user_id', $user->id)->value('id')
            : null;

        $appointmentsCount = $patientId
            ? Appointments::query()->where('patient_id', $patientId)->count()
            : 0;
        $upcomingCount = $patientId
            ? Appointments::query()
                ->where('patient_id', $patientId)
                ->whereDate('date', '>=', today())
                ->where('status', 'scheduled')
                ->count()
            : 0;
        $prescriptionsCount = $patientId
            ? Prescription::query()->where('patient_id', $patientId)->count()
            : 0;
        $outstandingAmount = $patientId
            ? (float) BillingPayment::query()->where('patient_id', $patientId)->sum('balance')
            : 0.0;
        $paymentsCount = $patientId
            ? BillingPayment::query()->where('patient_id', $patientId)->count()
            : 0;

        return [
            Stat::make('My Appointments', (string) $appointmentsCount)
                ->description('All my bookings')
                ->icon('heroicon-o-calendar-days')
                ->url(AppointmentsResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#1D4ED8')),
            Stat::make('Upcoming', (string) $upcomingCount)
                ->description('Scheduled from today')
                ->icon('heroicon-o-clock')
                ->url(AppointmentsResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#0EA5E9')),
            Stat::make('My Prescriptions', (string) $prescriptionsCount)
                ->description('Issued for me')
                ->icon('heroicon-o-document-text')
                ->url(PrescriptionResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#7C3AED')),
            Stat::make('Outstanding Bill', '$' . number_format($outstandingAmount, 2))
                ->description('My balance due')
                ->icon('heroicon-o-exclamation-triangle')
                ->url(BillingPaymentResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#B91C1C')),
            Stat::make('My Payments', (string) $paymentsCount)
                ->description('Billing records')
                ->icon('heroicon-o-credit-card')
                ->url(BillingPaymentResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#059669')),
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
