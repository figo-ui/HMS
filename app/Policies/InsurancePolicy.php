<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Insurance;
use Illuminate\Auth\Access\HandlesAuthorization;

class InsurancePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Insurance');
    }

    public function view(AuthUser $authUser, Insurance $insurance): bool
    {
        return $authUser->can('View:Insurance');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Insurance');
    }

    public function update(AuthUser $authUser, Insurance $insurance): bool
    {
        return $authUser->can('Update:Insurance');
    }

    public function delete(AuthUser $authUser, Insurance $insurance): bool
    {
        return $authUser->can('Delete:Insurance');
    }

    public function restore(AuthUser $authUser, Insurance $insurance): bool
    {
        return $authUser->can('Restore:Insurance');
    }

    public function forceDelete(AuthUser $authUser, Insurance $insurance): bool
    {
        return $authUser->can('ForceDelete:Insurance');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Insurance');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Insurance');
    }

    public function replicate(AuthUser $authUser, Insurance $insurance): bool
    {
        return $authUser->can('Replicate:Insurance');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Insurance');
    }

}