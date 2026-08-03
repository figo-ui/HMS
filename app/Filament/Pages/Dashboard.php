<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\HospitalAppointmentsPieChart;
use App\Filament\Widgets\HospitalIncomeExpenseChart;
use App\Filament\Widgets\HospitalReportsTypeChart;
use App\Filament\Widgets\HospitalStatsOverview;
use App\Filament\Widgets\CashierOverview;
use App\Filament\Widgets\CashierPaymentModeChart;
use App\Filament\Widgets\CashierQueue;
use App\Filament\Widgets\RoleBillingOverview;
use App\Filament\Widgets\RoleBillingStatusChart;
use App\Filament\Widgets\RoleDoctorWorkloadOverview;
use App\Filament\Widgets\RoleDoctorWeeklyAppointmentsChart;
use App\Filament\Widgets\RoleOperationsDepartmentChart;
use App\Filament\Widgets\RolePharmacyOverview;
use App\Filament\Widgets\RolePatientFlowOverview;
use App\Filament\Widgets\RolePatientAppointmentsTrendChart;
use App\Filament\Widgets\RolePatientSelfServiceOverview;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Str;

class Dashboard extends BaseDashboard
{
    public function getTitle(): string
    {
        return $this->getDynamicDashboardTitle();
    }

    public function getHeading(): string
    {
        return $this->getDynamicDashboardTitle();
    }

    public function getWidgets(): array
    {
        $role = $this->getCurrentRole();

        if (in_array($role, ['admin', 'super_admin'], true)) {
            return [
                HospitalStatsOverview::class,
                CashierOverview::class,
                CashierQueue::class,
                CashierPaymentModeChart::class,
                HospitalIncomeExpenseChart::class,
                HospitalAppointmentsPieChart::class,
                HospitalReportsTypeChart::class,
            ];
        }

        if (in_array($role, ['doctor', 'nurse', 'triage_nurse'], true)) {
            return [
                RoleDoctorWorkloadOverview::class,
                RoleDoctorWeeklyAppointmentsChart::class,
                HospitalAppointmentsPieChart::class,
                HospitalReportsTypeChart::class,
            ];
        }

        if (in_array($role, ['opd_staff', 'ipd_staff', 'receptionist'], true)) {
            return [
                RolePatientFlowOverview::class,
                RoleOperationsDepartmentChart::class,
                HospitalAppointmentsPieChart::class,
                HospitalIncomeExpenseChart::class,
            ];
        }

        if (in_array($role, ['cashier', 'casher', 'billing_staff', 'accountant'], true)) {
            return [
                CashierOverview::class,
                CashierQueue::class,
                CashierPaymentModeChart::class,
            ];
        }

        if (in_array($role, ['lab_staff', 'laboratory_staff', 'radiology_staff'], true)) {
            return [
                RolePatientFlowOverview::class,
                RoleOperationsDepartmentChart::class,
                HospitalReportsTypeChart::class,
                HospitalStatsOverview::class,
            ];
        }

        if (in_array($role, ['pharmacy_staff', 'pharmacist'], true)) {
            return [
                RolePharmacyOverview::class,
              
            ];
        }

        if ($role === 'patient') {
            return [
                RolePatientSelfServiceOverview::class,
                RolePatientAppointmentsTrendChart::class,
            ];
        }

        return [
            HospitalStatsOverview::class,
            HospitalAppointmentsPieChart::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }

    protected function getDynamicDashboardTitle(): string
    {
        $user = auth()->user();
        $identifier = $user?->roles?->first()?->name ?? $user?->name ?? 'User';

        return 'Dashboard of ' . $identifier;
    }

    protected function getCurrentRole(): string
    {
        $role = auth()->user()?->roles?->first()?->name;

        return Str::of((string) ($role ?? 'user'))->lower()->value();
    }
}
