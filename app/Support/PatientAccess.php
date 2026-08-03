<?php

namespace App\Support;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PatientAccess
{
    /**
     * @return list<string>
     */
    public static function permissions(): array
    {
        return [
            'ViewAny:Patients',
            'View:Patients',
            'Update:Patients',
            'ViewAny:Appointments',
            'View:Appointments',
            'Create:Appointments',
            'ViewAny:BillingPayment',
            'View:BillingPayment',
            'ViewAny:Prescription',
            'View:Prescription',
            'ViewAny:Laboratory',
            'View:Laboratory',
            'ViewAny:Radiology',
            'View:Radiology',
            'ViewAny:Pharmacy',
            'View:Pharmacy',
            'ViewAny:Insurance',
            'View:Insurance',
            'ViewAny:OPD',
            'View:OPD',
            'ViewAny:IPD',
            'View:IPD',
            'ViewAny:Triage',
            'View:Triage',
        ];
    }

    public static function ensureRolePermissions(): Role
    {
        $role = Role::findOrCreate('patient');

        foreach (static::permissions() as $permission) {
            Permission::findOrCreate($permission);
        }

        $role->givePermissionTo(static::permissions());

        return $role;
    }
}
