<?php

namespace App\Policies;

use App\Models\Income;
use App\Models\User;

class IncomePolicy
{
    /**
     * Determine whether the user can view any income records.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('member');
    }

    /**
     * Determine whether the user can view a specific income record.
     */
    public function view(User $user, Income $income): bool
    {
        return $user->hasRole('adm      in') || $user->hasRole('member');
    }

    /**
     * Determine whether the user can create income records.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the income record.
     */
    public function update(User $user, Income $income): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the income record.
     */
    public function delete(User $user, Income $income): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the income record.
     */
    public function restore(User $user, Income $income): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the income record.
     */
    public function forceDelete(User $user, Income $income): bool
    {
        return false;
    }
}
