<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Settings extends Model
{
    protected $fillable = [
        'hospital_profile',
        'departments',
        'service_charges',
        'tax_rules',
        'roles_permissions',
        'email_sms_settings',
        'backup_settings',
        'audit_id',
        'user_id',
        'action',
        'module',
        'record_id',
        'old_value',
        'new_value',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'hospital_profile' => 'array',
        'departments' => 'array',
        'service_charges' => 'array',
        'tax_rules' => 'array',
        'roles_permissions' => 'array',
        'email_sms_settings' => 'array',
        'backup_settings' => 'array',
        'old_value' => 'array',
        'new_value' => 'array',
        'created_at' => 'datetime',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
