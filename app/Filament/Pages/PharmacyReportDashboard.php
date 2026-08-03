<?php

namespace App\Filament\Pages;

use App\Services\PharmacyReportService;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class PharmacyReportDashboard extends Page
{
    use HasPageShield;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationLabel = 'Pharmacy Reports';

    protected static ?int $navigationSort = 40;

    protected string $view = 'filament.pages.pharmacy-report-dashboard';

    public ?string $startDate = null;

    public ?string $endDate = null;

    public function mount(): void
    {
        $this->startDate = request()->query('start_date', now()->startOfMonth()->toDateString());
        $this->endDate = request()->query('end_date', now()->toDateString());
    }

    public function getViewData(): array
    {
        $report = PharmacyReportService::build($this->startDate, $this->endDate);

        return [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'summary' => $report['summary'],
            'recentTransactions' => $report['recent_transactions'],
            'recentSales' => $report['recent_sales'],
            'topMedicines' => $report['top_medicines'],
            'csvUrl' => route('pharmacy-reports.export.csv', [
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ]),
            'printUrl' => route('pharmacy-reports.print', [
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ]),
        ];
    }
}
