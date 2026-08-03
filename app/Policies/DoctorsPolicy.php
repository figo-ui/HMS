<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Doctors;
use Illuminate\Auth\Access\HandlesAuthorization;

class DoctorsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Doctors');
    }

    public function view(AuthUser $authUser, Doctors $doctors): bool
    {
        return $authUser->can('View:Doctors');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Doctors');
    }

    public function update(AuthUser $authUser, Doctors $doctors): bool
    {
        return $authUser->can('Update:Doctors');
    }

    public function delete(AuthUser $authUser, Doctors $doctors): bool
    {
        return $authUser->can('Delete:Doctors');
    }

    public function restore(AuthUser $authUser, Doctors $doctors): bool
    {
        return $authUser->can('Restore:Doctors');
    }

    public function forceDelete(AuthUser $authUser, Doctors $doctors): bool
    {
        return $authUser->can('ForceDelete:Doctors');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Doctors');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Doctors');
    }

    public function replicate(AuthUser $authUser, Doctors $doctors): bool
    {
        return $authUser->can('Replicate:Doctors');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Doctors');
    }

}