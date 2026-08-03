<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\OPD;
use Illuminate\Auth\Access\HandlesAuthorization;

class OPDPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:OPD');
    }

    public function view(AuthUser $authUser, OPD $oPD): bool
    {
        return $authUser->can('View:OPD');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:OPD');
    }

    public function update(AuthUser $authUser, OPD $oPD): bool
    {
        return $authUser->can('Update:OPD');
    }

    public function delete(AuthUser $authUser, OPD $oPD): bool
    {
        return $authUser->can('Delete:OPD');
    }

    public function restore(AuthUser $authUser, OPD $oPD): bool
    {
        return $authUser->can('Restore:OPD');
    }

    public function forceDelete(AuthUser $authUser, OPD $oPD): bool
    {
        return $authUser->can('ForceDelete:OPD');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:OPD');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:OPD');
    }

    public function replicate(AuthUser $authUser, OPD $oPD): bool
    {
        return $authUser->can('Replicate:OPD');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:OPD');
    }

}