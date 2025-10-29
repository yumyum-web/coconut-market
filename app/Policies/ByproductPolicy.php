<?php

namespace App\Policies;

use App\Models\Byproduct;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ByproductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Both farmers and buyers can view byproducts
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Byproduct $byproduct): bool
    {
        return true; // Anyone authenticated can view a byproduct
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
    public function update(User $user, Byproduct $byproduct): bool
    {
        return $user->id === $byproduct->farmer_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Byproduct $byproduct): bool
    {
        return $user->id === $byproduct->farmer_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Byproduct $byproduct): bool
    {
        return $user->id === $byproduct->farmer_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Byproduct $byproduct): bool
    {
        return $user->id === $byproduct->farmer_id;
    }
}
