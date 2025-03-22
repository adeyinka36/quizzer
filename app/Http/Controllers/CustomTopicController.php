<?php

namespace App\Http\Controllers;

use App\Http\Resources\TopicResource;
use App\Models\Topic;
use Illuminate\Http\Request;

class CustomTopicController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        $topicsQuery = Topic::where('is_custom', true);

        if ($search) {
            $topicsQuery->where('title', 'like', '%' . $search . '%');
        }

        $topics = $topicsQuery->paginate(10)->appends($request->query());

        return response()->json([
            '_links' => [
                'self' => url()->current() . '?' . http_build_query($request->query()),
                'next' => $topics->nextPageUrl(),
                'prev' => $topics->previousPageUrl(),
            ],
            'data' => TopicResource::collection($topics),
        ], 200);
    }
}
