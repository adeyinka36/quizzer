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
        $game = $event->game;
        $game->creator->sendGameInvite($game);
    }
}
