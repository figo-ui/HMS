<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Patients;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatientsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Patients');
    }

    public function view(AuthUser $authUser, Patients $patients): bool
    {
        return $authUser->can('View:Patients');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Patients');
    }

    public function update(AuthUser $authUser, Patients $patients): bool
    {
        return $authUser->can('Update:Patients');
    }

    public function delete(AuthUser $authUser, Patients $patients): bool
    {
        return $authUser->can('Delete:Patients');
    }

    public function restore(AuthUser $authUser, Patients $patients): bool
    {
        return $authUser->can('Restore:Patients');
    }

    public function forceDelete(AuthUser $authUser, Patients $patients): bool
    {
        return $authUser->can('ForceDelete:Patients');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Patients');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Patients');
    }

    public function replicate(AuthUser $authUser, Patients $patients): bool
    {
        return $authUser->can('Replicate:Patients');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Patients');
    }

}