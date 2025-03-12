<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Models\Game;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|uuid|exists:games,id',
        ]);

        $gameId = $validated['game_id'];

        $game = Game::withQuestionsAndOptions()->findOrFail($gameId);

        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/v1/questions/'.$game,
                ],
            ],
            'data' => QuestionResource::collection($game->questions),
        ]);
    }
}
