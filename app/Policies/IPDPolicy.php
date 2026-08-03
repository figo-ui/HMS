<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\IPD;
use Illuminate\Auth\Access\HandlesAuthorization;

class IPDPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:IPD');
    }

    public function view(AuthUser $authUser, IPD $iPD): bool
    {
        return $authUser->can('View:IPD');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:IPD');
    }

    public function update(AuthUser $authUser, IPD $iPD): bool
    {
        return $authUser->can('Update:IPD');
    }

    public function delete(AuthUser $authUser, IPD $iPD): bool
    {
        return $authUser->can('Delete:IPD');
    }

    public function restore(AuthUser $authUser, IPD $iPD): bool
    {
        return $authUser->can('Restore:IPD');
    }

    public function forceDelete(AuthUser $authUser, IPD $iPD): bool
    {
        return $authUser->can('ForceDelete:IPD');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:IPD');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:IPD');
    }

    public function replicate(AuthUser $authUser, IPD $iPD): bool
    {
        return $authUser->can('Replicate:IPD');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:IPD');
    }

}