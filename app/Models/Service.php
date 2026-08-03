<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'code', 'name', 'service_type', 'department_id', 
        'price', 'insurance_coverage_percent', 'requires_pre_auth',
        'is_active', 'metadata'
    ];
    
    protected $casts = [
        'metadata' => 'array',
        'price' => 'decimal:2',
        'insurance_coverage_percent' => 'decimal:2',
        'is_active' => 'boolean',
        'requires_pre_auth' => 'boolean'
    ];
    
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    
    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }
    
    public function calculatePatientShare(Patients $patient): float
    {
        if (!$patient->insurance || !$this->insurance_coverage_percent) {
            return $this->price;
        }
        
        $insuranceShare = ($this->price * $this->insurance_coverage_percent) / 100;
        return $this->price - $insuranceShare;
    }
    
    public function calculateInsuranceShare(Patients $patient): float
    {
        if (!$patient->insurance || !$this->insurance_coverage_percent) {
            return 0;
        }
        
        return ($this->price * $this->insurance_coverage_percent) / 100;
    }
    
    // Scopes
    public function scopeLabServices($query)
    {
        return $query->where('service_type', 'lab')->where('is_active', true);
    }
    
    public function scopeRadiologyServices($query)
    {
        return $query->where('service_type', 'radiology')->where('is_active', true);
    }
    
    public function scopePharmacyServices($query)
    {
        return $query->where('service_type', 'pharmacy')->where('is_active', true);
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}