<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Settings;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Settings');
    }

    public function view(AuthUser $authUser, Settings $settings): bool
    {
        return $authUser->can('View:Settings');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Settings');
    }

    public function update(AuthUser $authUser, Settings $settings): bool
    {
        return $authUser->can('Update:Settings');
    }

    public function delete(AuthUser $authUser, Settings $settings): bool
    {
        return $authUser->can('Delete:Settings');
    }

    public function restore(AuthUser $authUser, Settings $settings): bool
    {
        return $authUser->can('Restore:Settings');
    }

    public function forceDelete(AuthUser $authUser, Settings $settings): bool
    {
        return $authUser->can('ForceDelete:Settings');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Settings');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Settings');
    }

    public function replicate(AuthUser $authUser, Settings $settings): bool
    {
        return $authUser->can('Replicate:Settings');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Settings');
    }

}