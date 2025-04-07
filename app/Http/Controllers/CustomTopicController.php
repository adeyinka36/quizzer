<?php

namespace App\Http\Controllers;

use App\Http\Resources\TopicResource;
use App\Models\Topic;
use Illuminate\Http\Request;


class CustomTopicController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q'); // or input('q')

        $topics = Topic::where('is_custom', true)
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->paginate(10)
            ->appends($request->query());

        return response()->json([
            '_links' => [
                'self' => url()->current() . '?' . http_build_query($request->query()),
                'next' => $topics->nextPageUrl(),
                'prev' => $topics->previousPageUrl(),
            ],
            'data' => TopicResource::collection($topics),
        ], 200);
    }


    public function store(Request $request)
    {
        $title = $request->title;
        // check if the topic title already exists in the database. Convert to small letter and if its 2 words seperated by space try ib reverse order too
        $titleNormalized = strtolower($title);

        $topic = Topic::where('title', $titleNormalized)
            ->first();

        if($topic) {
            return response()->json([
                '_links' => [
                    'self' => [
                        'href' => 'api/v1/topics/'. $title,
                    ],
                ],
                'message' => 'Topic already exists',
                'data' => new TopicResource($topic),
            ], 200);
        }

        //todo: make request to generate quiz
        $createdTopic = Topic::inRandomOrder()->first();
        $createdTopic->title = $title;
        $createdTopic->is_custom = true;
        $createdTopic->save();

        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/v1/topics/'.$createdTopic->id,
                ],
            ],
            'message' => 'Topic created successfully',
            'data' => new TopicResource($createdTopic),
        ], 201);
    }

}
