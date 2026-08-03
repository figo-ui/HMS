<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Laboratory;
use Illuminate\Auth\Access\HandlesAuthorization;

class LaboratoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Laboratory');
    }

    public function view(AuthUser $authUser, Laboratory $laboratory): bool
    {
        return $authUser->can('View:Laboratory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Laboratory');
    }

    public function update(AuthUser $authUser, Laboratory $laboratory): bool
    {
        return $authUser->can('Update:Laboratory');
    }

    public function delete(AuthUser $authUser, Laboratory $laboratory): bool
    {
        return $authUser->can('Delete:Laboratory');
    }

    public function restore(AuthUser $authUser, Laboratory $laboratory): bool
    {
        return $authUser->can('Restore:Laboratory');
    }

    public function forceDelete(AuthUser $authUser, Laboratory $laboratory): bool
    {
        return $authUser->can('ForceDelete:Laboratory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Laboratory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Laboratory');
    }

    public function replicate(AuthUser $authUser, Laboratory $laboratory): bool
    {
        return $authUser->can('Replicate:Laboratory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Laboratory');
    }

}