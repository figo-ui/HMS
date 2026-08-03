<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pharmacy extends Model
{
    protected $fillable = [
        'medicine_id',
        'medicine_name',
        'batch_no',
        'expiry_date',
        'stock_qty',
        'unit_price',
        'supplier_id',
        'reorder_level',
        'prescription_sale_id',
        'issued_to_patient_id',
        'received_at',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'received_at' => 'datetime',
        'unit_price' => 'decimal:2',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patients::class, 'issued_to_patient_id');
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'medicine_id', 'item_id');
    }

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class, 'prescription_sale_id', 'prescription_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(PharmacyMovement::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(PharmacySale::class);
    }
}
