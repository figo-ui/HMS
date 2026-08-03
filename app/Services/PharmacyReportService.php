<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\PharmacySale;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PharmacyReportService
{
    public static function build(?string $startDate = null, ?string $endDate = null): array
    {
        [$start, $end] = static::resolveRange($startDate, $endDate);

        $transactionsQuery = InventoryTransaction::query()
            ->with('inventory')
            ->whereBetween('created_at', [$start, $end]);

        $salesQuery = PharmacySale::query()
            ->with(['patient', 'seller'])
            ->whereBetween('sold_at', [$start, $end]);

        $purchaseQty = (int) (clone $transactionsQuery)
            ->where('operation_type', 'purchase')
            ->sum('quantity');

        $purchaseValue = (float) (clone $transactionsQuery)
            ->where('operation_type', 'purchase')
            ->get()
            ->sum(fn (InventoryTransaction $transaction): float => abs((float) ($transaction->inventory?->purchase_price ?? 0) * (int) $transaction->quantity));

        $dispenseQty = (int) abs((int) (clone $transactionsQuery)
            ->where('operation_type', 'dispense')
            ->sum('quantity'));

        $directSaleQty = (int) (clone $salesQuery)
            ->where('sale_type', 'direct_sale')
            ->sum('quantity');

        $prescriptionSaleQty = (int) (clone $salesQuery)
            ->where('sale_type', 'prescription')
            ->sum('quantity');

        $salesValue = (float) (clone $salesQuery)->sum('total_amount');

        $recentTransactions = (clone $transactionsQuery)
            ->latest()
            ->limit(12)
            ->get();

        $recentSales = (clone $salesQuery)
            ->latest('sold_at')
            ->limit(12)
            ->get();

        $topMedicines = (clone $salesQuery)
            ->selectRaw('medicine_name, SUM(quantity) as total_quantity, SUM(total_amount) as total_amount')
            ->groupBy('medicine_name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        return [
            'start' => $start,
            'end' => $end,
            'summary' => [
                'purchase_qty' => $purchaseQty,
                'purchase_value' => $purchaseValue,
                'direct_sale_qty' => $directSaleQty,
                'prescription_sale_qty' => $prescriptionSaleQty,
                'dispense_qty' => $dispenseQty,
                'sales_value' => $salesValue,
            ],
            'recent_transactions' => $recentTransactions,
            'recent_sales' => $recentSales,
            'top_medicines' => $topMedicines,
        ];
    }

    public static function rowsForCsv(?string $startDate = null, ?string $endDate = null): Collection
    {
        $report = static::build($startDate, $endDate);

        return collect($report['recent_sales'])->map(function (PharmacySale $sale): array {
            return [
                'sale_id' => $sale->sale_id,
                'sale_type' => $sale->sale_type,
                'patient' => $sale->patient?->full_name,
                'prescription_id' => $sale->prescription_id,
                'medicine_name' => $sale->medicine_name,
                'quantity' => $sale->quantity,
                'unit_price' => $sale->unit_price,
                'total_amount' => $sale->total_amount,
                'payment_status' => $sale->payment_status,
                'sold_by' => $sale->seller?->name,
                'sold_at' => optional($sale->sold_at)->format('Y-m-d H:i:s'),
            ];
        });
    }

    protected static function resolveRange(?string $startDate, ?string $endDate): array
    {
        $start = filled($startDate)
            ? Carbon::parse($startDate)->startOfDay()
            : now()->startOfMonth()->startOfDay();

        $end = filled($endDate)
            ? Carbon::parse($endDate)->endOfDay()
            : now()->endOfDay();

        if ($start->gt($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        return [$start, $end];
    }
}
