<?php

namespace App\Http\Controllers;

use App\Events\GameCreated;
use App\Http\Requests\GameCreateUpdateRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        Log::info('game getting created---------');
        $data = $request->validated();
        $playerIds = $request->input('players', []);
        unset($data['players']);

        $game = Game::withoutEvents(function () use ($data) {
            return Game::create($data);
        });


        if (empty($playerIds)) {
            return response()->json([
                'message' => 'No players provided.',
            ], 422);
        }

        $game->players()->attach($playerIds);
        $game->load('players'); // Ensure players are loaded in memory

        Log::info('game getting created---------', [
            'game' => $game,
            'players' => $game->players,
        ]);
        event(new GameCreated($game));

        return response()->json([
            '_links' => [
                'self' => [
                    'href' => route('games.show', $game->id),
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
