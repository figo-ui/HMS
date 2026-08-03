<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reports extends Model
{
    protected $fillable = [
        'report_id',
        'report_type',
        'date_range',
        'filters',
        'generated_by',
        'generated_at',
        'format',
    ];

    protected $casts = [
        'date_range' => 'array',
        'filters' => 'array',
        'generated_at' => 'datetime',
    ];

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'generated_by', 'staff_id');
    }
}
