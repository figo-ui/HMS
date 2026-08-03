<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\PharmacyReportDashboard;
use App\Filament\Resources\Inventories\InventoryResource;
use App\Filament\Resources\Pharmacies\PharmacyResource;
use App\Filament\Resources\PharmacySales\PharmacySaleResource;
use App\Filament\Resources\Prescriptions\PrescriptionResource;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\PharmacySale;
use App\Models\Prescription;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RolePharmacyOverview extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $todayPrescriptions = Prescription::query()
            ->whereDate('created_at', today())
            ->count();

        $todayDispensed = abs((int) InventoryTransaction::query()
            ->where('operation_type', 'dispense')
            ->whereDate('created_at', today())
            ->sum('quantity'));

        $todaySales = (float) PharmacySale::query()
            ->whereDate('sold_at', today())
            ->sum('total_amount');

        $lowStock = Inventory::query()
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->count();

        $expiredSoon = Inventory::query()
            ->whereDate('expiry_date', '>=', today())
            ->whereDate('expiry_date', '<=', today()->copy()->addDays(30))
            ->count();

        return [
            Stat::make('Today Prescriptions', (string) $todayPrescriptions)
                ->description('New prescriptions created today')
                ->icon('heroicon-o-document-text')
                 ->color('#0c0c0c')
                ->url(PrescriptionResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#0f766e')),
            Stat::make('Today Dispensed', (string) $todayDispensed)
                ->description('Medicines dispensed today')
                ->icon('heroicon-o-check-badge')
                ->color('#0c0c0c')
                ->url(PharmacyResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#2563eb')),
            Stat::make('Today Sales', 'ETB' . number_format($todaySales, 2))
                ->description('Pharmacy sales recorded today')
                ->icon('heroicon-o-banknotes')
                 ->color('#0c0c0c')
                ->url(PharmacySaleResource::getUrl('index'))
                ->extraAttributes($this->cardStyle('#7c3aed')),
            Stat::make('Low Stock', (string) $lowStock)
                ->description('Items at or below reorder level')
                ->icon('heroicon-o-exclamation-triangle')
                ->url(InventoryResource::getUrl('index'))
                 ->color('#0c0c0c')
                ->extraAttributes($this->cardStyle('#d97706')),
            Stat::make('Expired Soon', (string) $expiredSoon)
                ->description('Items expiring in the next 30 days')
                ->icon('heroicon-o-clock')
                 ->color('#0c0c0c')
                ->url(PharmacyReportDashboard::getUrl())
                ->extraAttributes($this->cardStyle('#22e9be')),
        ];
    }

    protected function getColumns(): int
    {
        return 5;
    }

    /**
     * @return array<string, string>
     */
    private function cardStyle(string $background, string $textColor = '#0a0a0a'): array
    {
        return [
            'style' => "background: {$background}; color: {$textColor}; border-radius: 0.8rem;",
            'class' => 'shadow-sm',
        ];
    }
}
