<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerCreationReqest;
use App\Http\Requests\PlayerUpdateRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PlayerController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function register(PlayerCreationReqest $request)
    {
        $player = Player::create([
            'first_name' => $request->input('last_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
        ]);

        $token = $player->createToken('player-'. $player->id,['control-own-resources'])->plainTextToken;

        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/players/' . $player->id,
                ]
            ],
            'data' => new PlayerResource($player),
            'token' => $token
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        $token = $player->createToken('player-'. $player->id,['control-own-resources'])->plainTextToken;
        return response()->json([
            '_links' => [
               'self' => [
                    'href' => 'api/players/' . $player->id,
                ]
            ],
            'data' => new PlayerResource($player),
            'token' => $token,
        ]);
    }

    public function login(Request $request) {
        // Authenticate the user
        $player = Player::where('email', $request->input('email'))->first();

        if (!$player ||!Hash::check($request->input('password'), $player->password)) {
            return response()->json([
               'message' => 'Invalid credentials',
            ], 401);
        }

        // Generate a new token
        $token = $player->createToken('player-'. $player->id, ['control-own-resources'])->plainTextToken;

        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/players/' . $player->id,
                ]
            ],
            'data' => new PlayerResource($player),
            'token' => $token
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlayerUpdateRequest $request, Player $player)
    {
        //Update player and return the updated
        $player->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            '_links' => [
               'self' => [
                    'href' => 'api/players/' . $player->id,
                ]
            ],
            'data' => new PlayerResource($player),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        //delete the player
        $player->delete();

        return response()->json([], 204);
    }
}
