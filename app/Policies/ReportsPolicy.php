<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Reports;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Reports');
    }

    public function view(AuthUser $authUser, Reports $reports): bool
    {
        return $authUser->can('View:Reports');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Reports');
    }

    public function update(AuthUser $authUser, Reports $reports): bool
    {
        return $authUser->can('Update:Reports');
    }

    public function delete(AuthUser $authUser, Reports $reports): bool
    {
        return $authUser->can('Delete:Reports');
    }

    public function restore(AuthUser $authUser, Reports $reports): bool
    {
        return $authUser->can('Restore:Reports');
    }

    public function forceDelete(AuthUser $authUser, Reports $reports): bool
    {
        return $authUser->can('ForceDelete:Reports');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Reports');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Reports');
    }

    public function replicate(AuthUser $authUser, Reports $reports): bool
    {
        return $authUser->can('Replicate:Reports');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Reports');
    }

}