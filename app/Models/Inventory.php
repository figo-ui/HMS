<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    protected $table = 'inventories';
    protected $primaryKey = 'item_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'item_id',
        'item_name',
        'category',
        'unit',
        'quantity',
        'reorder_level',
        'supplier_id',
        'purchase_price',
        'selling_price',
        'expiry_date',
        'store_location',
        'stock_movement_log',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class, 'inventory_id', 'item_id');
    }
}
