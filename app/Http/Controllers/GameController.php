<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameCreateUpdateRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Player $player, Request $request)
    {
        $games = $player->games()->paginate(10);
        $data = [
            "_links" => [
                "_self" => $games->url($games->currentPage()),
                "next" => $games->hasMorePages() ? $games->nextPageUrl() : null,
                "previous" => $games->onFirstPage() ? null : $games->previousPageUrl(),
            ],
            "count" => $games->count(),
            "total" => $games->total(),
            "data" => $games->items()
        ];

        return response()->json(($data), 200);
    }
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
