<?php

namespace App\Policies;

use App\Models\Player;
use App\Models\Topic;
use Illuminate\Auth\Access\Response;

class TopicPolicy
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
    public function view(Player $player, Topic $topic): bool
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
    public function update(Player $player, Topic $topic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Player $player, Topic $topic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Player $player, Topic $topic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Player $player, Topic $topic): bool
    {
        return false;
    }
}
