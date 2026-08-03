<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InventoryService
{
    public static function purchaseOrder(Inventory $inventory, int $quantity, string $referenceId, ?string $notes = null): InventoryTransaction
    {
        return static::apply($inventory, abs($quantity), 'purchase', $referenceId, $notes);
    }

    public static function saleInvoice(Inventory $inventory, int $quantity, string $referenceId, ?string $notes = null): InventoryTransaction
    {
        return static::apply($inventory, -abs($quantity), 'sale', $referenceId, $notes);
    }

    public static function dispensePrescription(Inventory $inventory, int $quantity, string $referenceId, ?string $notes = null): InventoryTransaction
    {
        return static::apply($inventory, -abs($quantity), 'dispense', $referenceId, $notes);
    }

    public static function removeExpired(Inventory $inventory, int $quantity, ?string $notes = null): InventoryTransaction
    {
        return static::apply($inventory, -abs($quantity), 'expired', 'EXP-' . now()->format('YmdHis'), $notes);
    }

    public static function adjustStock(Inventory $inventory, int $quantity, ?string $notes = null, ?string $referenceId = null): InventoryTransaction
    {
        if ($quantity === 0) {
            throw new RuntimeException('Adjustment quantity cannot be zero.');
        }

        return static::apply(
            $inventory,
            $quantity,
            'adjustment',
            $referenceId ?: 'ADJ-' . now()->format('YmdHis'),
            $notes
        );
    }

    protected static function apply(
        Inventory $inventory,
        int $delta,
        string $operationType,
        ?string $referenceId = null,
        ?string $notes = null,
    ): InventoryTransaction {
        if ($delta === 0) {
            throw new RuntimeException('Quantity must be greater than zero.');
        }

        return DB::transaction(function () use ($inventory, $delta, $operationType, $referenceId, $notes): InventoryTransaction {
            $item = Inventory::query()
                ->where('item_id', $inventory->item_id)
                ->lockForUpdate()
                ->firstOrFail();

            $nextQuantity = (int) $item->quantity + $delta;

            if ($nextQuantity < 0) {
                throw new RuntimeException("Insufficient stock for {$item->item_name}.");
            }

            $item->update([
                'quantity' => $nextQuantity,
            ]);

            return InventoryTransaction::query()->create([
                'inventory_id' => $item->item_id,
                'quantity' => $delta,
                'operation_type' => $operationType,
                'reference_id' => $referenceId,
                'notes' => $notes,
                'user_id' => Auth::id(),
            ]);
        });
    }
}
