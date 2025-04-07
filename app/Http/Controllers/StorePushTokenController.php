<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class StorePushTokenController extends Controller
{
    public function __invoke(Request $request)
    {
        // Validate input (use either 'token' or 'push_token' consistently)
        $data = $request->validate([
            'push_token' => 'required|string',
            'player_id' => 'required',
        ]);

        // Update the player's push token
        $player = Player::find($data['player_id']);
        $player->update(['push_token' => $data['push_token']]);

        return response()->json([
            'message' => 'Push token stored successfully',
            'data' => [
                'player_id' => $player->id,
                'push_token' => $player->push_token,
            ],
        ], 200);
    }
}
