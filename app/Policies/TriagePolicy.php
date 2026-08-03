<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Triage;
use Illuminate\Auth\Access\HandlesAuthorization;

class TriagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Triage');
    }

    public function view(AuthUser $authUser, Triage $triage): bool
    {
        return $authUser->can('View:Triage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Triage');
    }

    public function update(AuthUser $authUser, Triage $triage): bool
    {
        return $authUser->can('Update:Triage');
    }

    public function delete(AuthUser $authUser, Triage $triage): bool
    {
        return $authUser->can('Delete:Triage');
    }

    public function restore(AuthUser $authUser, Triage $triage): bool
    {
        return $authUser->can('Restore:Triage');
    }

    public function forceDelete(AuthUser $authUser, Triage $triage): bool
    {
        return $authUser->can('ForceDelete:Triage');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Triage');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Triage');
    }

    public function replicate(AuthUser $authUser, Triage $triage): bool
    {
        return $authUser->can('Replicate:Triage');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Triage');
    }

}