<?php

namespace App\Policies;

use App\Models\Harvest;
use App\Models\User;

class HarvestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Both farmers and buyers can view harvests
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Harvest $harvest): bool
    {
        return true; // Anyone authenticated can view a harvest
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->user_type === 'farmer';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Harvest $harvest): bool
    {
        return $user->id === $harvest->farmer_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Harvest $harvest): bool
    {
        return $user->id === $harvest->farmer_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Harvest $harvest): bool
    {
        return $user->id === $harvest->farmer_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Harvest $harvest): bool
    {
        return $user->id === $harvest->farmer_id;
    }
}
