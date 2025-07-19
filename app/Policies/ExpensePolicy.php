<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    /**
     * Determine whether the user can view any expense records.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view expense');
    }

    /**
     * Determine whether the user can view a specific expense record.
     */
    public function view(User $user, Expense $expense): bool
    {
        return $user->can('view expense');
    }

    /**
     * Determine whether the user can create expense records.
     */
    public function create(User $user): bool
    {
        return $user->can('create expense');
    }

    /**
     * Determine whether the user can update the expense record.
     */
    public function update(User $user, Expense $expense): bool
    {
        return $user->can('update expense');
    }

    /**
     * Determine whether the user can delete the expense record.
     */
    public function delete(User $user, Expense $expense): bool
    {
        return $user->can('delete expense');
    }

    /**
     * Determine whether the user can restore the expense record.
     */
    public function restore(User $user, Expense $expense): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the expense record.
     */
    public function forceDelete(User $user, Expense $expense): bool
    {
        return false;
    }
}
