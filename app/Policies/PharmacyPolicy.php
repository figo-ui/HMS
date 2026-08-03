<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Pharmacy;
use Illuminate\Auth\Access\HandlesAuthorization;

class PharmacyPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Pharmacy');
    }

    public function view(AuthUser $authUser, Pharmacy $pharmacy): bool
    {
        return $authUser->can('View:Pharmacy');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Pharmacy');
    }

    public function update(AuthUser $authUser, Pharmacy $pharmacy): bool
    {
        return $authUser->can('Update:Pharmacy');
    }

    public function delete(AuthUser $authUser, Pharmacy $pharmacy): bool
    {
        return $authUser->can('Delete:Pharmacy');
    }

    public function restore(AuthUser $authUser, Pharmacy $pharmacy): bool
    {
        return $authUser->can('Restore:Pharmacy');
    }

    public function forceDelete(AuthUser $authUser, Pharmacy $pharmacy): bool
    {
        return $authUser->can('ForceDelete:Pharmacy');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Pharmacy');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Pharmacy');
    }

    public function replicate(AuthUser $authUser, Pharmacy $pharmacy): bool
    {
        return $authUser->can('Replicate:Pharmacy');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Pharmacy');
    }

}