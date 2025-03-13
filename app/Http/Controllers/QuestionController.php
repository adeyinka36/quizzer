<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Models\Game;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'topic_id' => 'required|uuid|exists:topics,id',
        ]);

        $topicId = $validated['topic_id'];

        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/v1/questions/?topic_id='.$topicId,
                ],
            ],
            'data' => QuestionResource::collection(Question::where([
                'topic_id' => $topicId,
                'is_active' => true,
            ])->limit(10)->get()),
        ]);
    }
}
