<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Inventory;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Inventory');
    }

    public function view(AuthUser $authUser, Inventory $inventory): bool
    {
        return $authUser->can('View:Inventory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Inventory');
    }

    public function update(AuthUser $authUser, Inventory $inventory): bool
    {
        return $authUser->can('Update:Inventory');
    }

    public function delete(AuthUser $authUser, Inventory $inventory): bool
    {
        return $authUser->can('Delete:Inventory');
    }

    public function restore(AuthUser $authUser, Inventory $inventory): bool
    {
        return $authUser->can('Restore:Inventory');
    }

    public function forceDelete(AuthUser $authUser, Inventory $inventory): bool
    {
        return $authUser->can('ForceDelete:Inventory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Inventory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Inventory');
    }

    public function replicate(AuthUser $authUser, Inventory $inventory): bool
    {
        return $authUser->can('Replicate:Inventory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Inventory');
    }

}