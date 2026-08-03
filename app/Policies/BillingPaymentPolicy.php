<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BillingPayment;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillingPaymentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BillingPayment');
    }

    public function view(AuthUser $authUser, BillingPayment $billingPayment): bool
    {
        return $authUser->can('View:BillingPayment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BillingPayment');
    }

    public function update(AuthUser $authUser, BillingPayment $billingPayment): bool
    {
        return $authUser->can('Update:BillingPayment');
    }

    public function delete(AuthUser $authUser, BillingPayment $billingPayment): bool
    {
        return $authUser->can('Delete:BillingPayment');
    }

    public function restore(AuthUser $authUser, BillingPayment $billingPayment): bool
    {
        return $authUser->can('Restore:BillingPayment');
    }

    public function forceDelete(AuthUser $authUser, BillingPayment $billingPayment): bool
    {
        return $authUser->can('ForceDelete:BillingPayment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BillingPayment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BillingPayment');
    }

    public function replicate(AuthUser $authUser, BillingPayment $billingPayment): bool
    {
        return $authUser->can('Replicate:BillingPayment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BillingPayment');
    }

}