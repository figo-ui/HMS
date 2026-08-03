<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Appointments;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Appointments');
    }

    public function view(AuthUser $authUser, Appointments $appointments): bool
    {
        return $authUser->can('View:Appointments');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Appointments');
    }

    public function update(AuthUser $authUser, Appointments $appointments): bool
    {
        return $authUser->can('Update:Appointments');
    }

    public function delete(AuthUser $authUser, Appointments $appointments): bool
    {
        return $authUser->can('Delete:Appointments');
    }

    public function restore(AuthUser $authUser, Appointments $appointments): bool
    {
        return $authUser->can('Restore:Appointments');
    }

    public function forceDelete(AuthUser $authUser, Appointments $appointments): bool
    {
        return $authUser->can('ForceDelete:Appointments');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Appointments');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Appointments');
    }

    public function replicate(AuthUser $authUser, Appointments $appointments): bool
    {
        return $authUser->can('Replicate:Appointments');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Appointments');
    }

}