<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patients extends Model
{
    protected $fillable = [
        'user_id',
        'patient_id',
        'mrn',
        'full_name',
        'gender',
        'dob',
        'phone',
        'email',
        'address',
        'blood_group',
        'allergies',
        'emergency_contact',
        'insurance_id',
        'registered_at',
        'status',
    ];

/*    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
*/
    public function histories(): HasMany
    {
        return $this->hasMany(PatientHistory::class, 'patient_id')
            ->orderByDesc('occurred_at')
            ->orderByDesc('id');
    }

    public function triages(): HasMany
    {
        return $this->hasMany(Triage::class, 'patient_id');
    }

    public function insurance(): HasOne
    {
        return $this->hasOne(Insurance::class, 'patient_id');
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'patient_id');
    }
}
