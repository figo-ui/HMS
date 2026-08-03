<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Beds;
use Illuminate\Auth\Access\HandlesAuthorization;

class BedsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Beds');
    }

    public function view(AuthUser $authUser, Beds $beds): bool
    {
        return $authUser->can('View:Beds');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Beds');
    }

    public function update(AuthUser $authUser, Beds $beds): bool
    {
        return $authUser->can('Update:Beds');
    }

    public function delete(AuthUser $authUser, Beds $beds): bool
    {
        return $authUser->can('Delete:Beds');
    }

    public function restore(AuthUser $authUser, Beds $beds): bool
    {
        return $authUser->can('Restore:Beds');
    }

    public function forceDelete(AuthUser $authUser, Beds $beds): bool
    {
        return $authUser->can('ForceDelete:Beds');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Beds');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Beds');
    }

    public function replicate(AuthUser $authUser, Beds $beds): bool
    {
        return $authUser->can('Replicate:Beds');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Beds');
    }

}