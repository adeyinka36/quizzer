<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function show(Player $player, Request $request)
    {
        $stats = $player->getStats();

        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/v1/players/'.$player->id.'/stats',
                ],
            ],
            'data' => $stats,
        ], 200);
    }
}
