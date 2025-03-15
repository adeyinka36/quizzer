<?php

namespace App\Http\Controllers;

use App\Http\Resources\TopicResource;
use App\Models\Topic;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topics = Topic::where('is_custom', false)->get();


        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/v1/topics/',
                ],
            ],
            'data' => new TopicResource($topics),
        ], 201);
    }
}
