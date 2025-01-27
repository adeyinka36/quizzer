<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Models\GameQuestion;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|uuid|exists:games,id',
        ]);

        $questions = GameQuestion::where('game_id', $validated['game_id'])
            ->with('question.options')
            ->get();

        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/questions/' . $validated['game_id'],
                ]
            ],
            'data' => QuestionResource::collection($questions),
        ], 200);
    }
}
