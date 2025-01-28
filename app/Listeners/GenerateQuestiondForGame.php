<?php

namespace App\Listeners;

use App\Events\GameCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateQuestiondForGame
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }


//TODO: Make this a queued listener
    /**
     * Handle the event.
     */
    public function handle(GameCreated $event): void
    {
        //
    }
}
