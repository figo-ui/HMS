<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'inventory_id',
        'quantity',
        'operation_type',
        'reference_id',
        'notes',
        'user_id',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'item_id'); // Explicitly define foreign and local keys
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}