<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerCreationReqest;
use App\Http\Requests\PlayerUpdateRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Illuminate\Http\JsonResponse;
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
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
        ]);

        $token = $player->createToken('player-'.$player->id, ['control-own-resources', 'ability:can-view-questions'])->plainTextToken;

        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/v1/players/'.$player->id,
                ],
            ],
            'data' => new PlayerResource($player),
            'token' => $token,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        $token = $player->createToken('player-'.$player->id, ['control-own-resources', 'ability:can-view-questions'])->plainTextToken;

        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/v1/players/'.$player->id,
                ],
            ],
            'data' => new PlayerResource($player),
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        // Authenticate the user
        $player = Player::where('email', $request->input('email'))->first();

        if (! $player || ! Hash::check($request->input('password'), $player->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Generate a new token
        $token = $player->createToken('player-'.$player->id, ['control-own-resources', 'ability:can-view-questions'])->plainTextToken;

        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/v1/players/'.$player->id,
                ],
            ],
            'data' => new PlayerResource($player),
            'token' => $token,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlayerUpdateRequest $request, Player $player)
    {
//        //check curren_password is correct
//        if (!Hash::check($request->input('current_password'), $player->password)) {
//            return response()->json([
//                'message' => 'Invalid current password',
//            ], 401);
//        }

        $fieldsToUpdate = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
        ];

        if($request->input('new_password')) {
            $fieldsToUpdate['password'] = Hash::make($request->input('new_password'));
        }
        // Update player and return the updated
        $player->update($fieldsToUpdate);

        return response()->json([
            '_links' => [
                'self' => [
                    'href' => 'api/v1/players/'.$player->id,
                ],
            ],
            'data' => new PlayerResource($player),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        // delete the player
        $player->delete();

        return response()->json([], 204);
    }

    public function requestPasswordResetToken(Request $request): JsonResponse
    {
        // Reset the password
        $data = $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);

        $player = Player::where('email', $data['email'])->first();

        if (! $player) {
            return response()->json([
                'error' => 'Invalid email provided',
            ], 404);
        }

        $isCorrectEmailAndNames = strtolower($player->first_name) == strtolower($data['first_name']) &&
            strtolower($player->last_name) == strtolower($data['last_name']);

        if (! $isCorrectEmailAndNames) {
            return response()->json([
                'error' => 'Invalid names provided',
            ], 401);
        }

        $player->sendPasswordResetNotification($player);

        return response()->json([
            'message' => 'Password reset email sent',
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        // Update the password
        $data = $request->validate([
            'password' => 'required|string|min:8',
            'token' => 'required|string',
            'confirm_password' => 'required|string',
        ]);

        $player = Player::where('password_reset_token', $data['token'])->first();

        if (! $player) {
            return response()->json([
                'error' => 'Invalid token',
            ], 404);
        }

        if ($data['password'] != $data['confirm_password']) {
            return response()->json([
                'error' => 'Passwords do not match',
            ], 404);
        }

        $success = $player->updatePassword(Hash::make($data['password']), $data['token']);

        if (! $success) {
            return response()->json([
                'error' => 'Invalid token',
            ], 404);
        }

        return response()->json([
            'message' => 'Password updated',
        ]);
    }
}
