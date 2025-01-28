<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\Player;

class GamePolicy
{
    /**
     * Determine if the user can view the game.
     */
    public function view(Player $player, Game $game): bool
    {
        // Allow access if the user is part of the game
        return $game->players->contains($player->id);
    }

    /**
     * Determine if the user can update the game.
     */
    public function update(Player $player, Game $game): bool
    {
        return $game->creator_id === $player->id;
    }

    public function destroy(Player $player, Game $game): bool
    {
        return $game->creator_id === $player->id;
    }
}
