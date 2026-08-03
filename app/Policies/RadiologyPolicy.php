<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Radiology;
use Illuminate\Auth\Access\HandlesAuthorization;

class RadiologyPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Radiology');
    }

    public function view(AuthUser $authUser, Radiology $radiology): bool
    {
        return $authUser->can('View:Radiology');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Radiology');
    }

    public function update(AuthUser $authUser, Radiology $radiology): bool
    {
        return $authUser->can('Update:Radiology');
    }

    public function delete(AuthUser $authUser, Radiology $radiology): bool
    {
        return $authUser->can('Delete:Radiology');
    }

    public function restore(AuthUser $authUser, Radiology $radiology): bool
    {
        return $authUser->can('Restore:Radiology');
    }

    public function forceDelete(AuthUser $authUser, Radiology $radiology): bool
    {
        return $authUser->can('ForceDelete:Radiology');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Radiology');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Radiology');
    }

    public function replicate(AuthUser $authUser, Radiology $radiology): bool
    {
        return $authUser->can('Replicate:Radiology');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Radiology');
    }

}