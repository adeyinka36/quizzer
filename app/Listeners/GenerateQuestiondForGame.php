<?php

namespace App\Listeners;

use App\Events\GameCreated;
use App\Models\Question;
use Illuminate\Support\Facades\Log;

class GenerateQuestiondForGame
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    // TODO: Make this a queued listener
    /**
     * Handle the event.
     */
    public function handle(GameCreated $event): void
    {
        $game = $event->game;
        $topic = $game->topic;

        $questions = Question::where('topic_id', $topic->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(7)
            ->get();

        foreach ($questions as $question) {
            $question->update([
                'game_id' => $game->id,
            ]);
        }
    }

}
