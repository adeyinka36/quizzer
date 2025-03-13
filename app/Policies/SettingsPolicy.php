<?php

namespace App\Policies;

use App\Models\Player;
use App\Models\Settings;
use Illuminate\Auth\Access\Response;

class SettingsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Player $player): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Player $player, Settings $settings): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Player $player): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Player $player, Settings $settings): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Player $player, Settings $settings): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Player $player, Settings $settings): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Player $player, Settings $settings): bool
    {
        return false;
    }
}
