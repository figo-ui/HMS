<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    protected $fillable = [
        'staff_id',
        'full_name',
        'role',
        'department',
        'department_id',
        'shift',
        'phone',
        'email',
        'join_date',
        'salary',
        'status',
    ];

    protected $casts = [
        'join_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function createdAppointments(): HasMany
    {
        return $this->hasMany(Appointments::class, 'created_by', 'staff_id');
    }

    public function assignedBeds(): HasMany
    {
        return $this->hasMany(Beds::class, 'assigned_nurse', 'staff_id');
    }

    public function generatedReports(): HasMany
    {
        return $this->hasMany(Reports::class, 'generated_by', 'staff_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
