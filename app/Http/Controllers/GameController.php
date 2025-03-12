<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameCreateUpdateRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;

class GameController extends Controller
{
    public function store(GameCreateUpdateRequest $request)
    {
        $data = $request->validated();
        $game = Game::create($data);

        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/v1/games/'.$game->id,
                ],
            ],
            'data' => new GameResource($game),
        ], 201);
    }

    public function update(GameCreateUpdateRequest $request, Game $game)
    {
        $data = $request->validated();
        $game->update($data);

        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/v1/games/'.$game->id,
                ],
            ],
            'data' => new GameResource($game),
        ], 200);
    }

    public function show(Game $game)
    {
        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/v1/games/'.$game->id,
                ],
            ],
            'data' => new GameResource($game),
        ], 200);
    }

    public function destroy(Game $game)
    {
        $game->delete();

        return response()->json([], 204);
    }
}
