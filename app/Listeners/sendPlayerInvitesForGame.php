<?php

namespace App\Listeners;

use App\Events\GameCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class sendPlayerInvitesForGame
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GameCreated $event): void
    {
        Log::info('GameCreated event triggered listener', [
            'game_id' => $event->game->id,
            'game_name' => $event->game->name,
            'players' => $event->game->players()->pluck('id'),
        ]);
        $game = $event->game;
        $game->players()->each(function ($player) use ($game) {
            $player->sendGameInvite($game);
        });
    }
}
