<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nurse extends Model
{
    protected $fillable = [
        'nurse_id',
        'full_name',
        'gender',
        'phone',
        'email',
        'department_id',
        'license_no',
        'shift',
        'join_date',
        'status',
        'emergency_contact',
    ];

    protected $casts = [
        'join_date' => 'date',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
