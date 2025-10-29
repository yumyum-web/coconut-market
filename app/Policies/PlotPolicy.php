<?php

namespace App\Policies;

use App\Models\Plot;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PlotPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->user_type === 'farmer';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Plot $plot): bool
    {
        return $user->id === $plot->farmer_id;
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
    public function update(User $user, Plot $plot): bool
    {
        return $user->id === $plot->farmer_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Plot $plot): bool
    {
        return $user->id === $plot->farmer_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Plot $plot): bool
    {
        return $user->id === $plot->farmer_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Plot $plot): bool
    {
        return $user->id === $plot->farmer_id;
    }
}
