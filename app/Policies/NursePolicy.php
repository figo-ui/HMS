<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Nurse;
use Illuminate\Auth\Access\HandlesAuthorization;

class NursePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Nurse');
    }

    public function view(AuthUser $authUser, Nurse $nurse): bool
    {
        return $authUser->can('View:Nurse');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Nurse');
    }

    public function update(AuthUser $authUser, Nurse $nurse): bool
    {
        return $authUser->can('Update:Nurse');
    }

    public function delete(AuthUser $authUser, Nurse $nurse): bool
    {
        return $authUser->can('Delete:Nurse');
    }

    public function restore(AuthUser $authUser, Nurse $nurse): bool
    {
        return $authUser->can('Restore:Nurse');
    }

    public function forceDelete(AuthUser $authUser, Nurse $nurse): bool
    {
        return $authUser->can('ForceDelete:Nurse');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Nurse');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Nurse');
    }

    public function replicate(AuthUser $authUser, Nurse $nurse): bool
    {
        return $authUser->can('Replicate:Nurse');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Nurse');
    }

}