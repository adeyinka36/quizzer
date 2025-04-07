<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Friendship;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Mail\Transport\ResendTransport;
use Illuminate\Support\Facades\Log;

class FriendshipController extends Controller
{
    /**
     * Show a paginated list of players related to the given $player, filtered by status.
     *
     * Valid statuses: 'sent', 'received', 'accepted'
     * If an invalid status is provided, return a 400 error.
     */
    public function show(Player $player, Request $request)
    {
        $status = $request->query('status'); // 'sent', 'received', or 'accepted'
        $search = $request->query('q');      // e.g. 'alice'
        $perPage = 10;

        // 1) Base relationship (belongsToMany returning Player models)
        $relation = match ($status) {
            'sent'     => $player->sentPlayerRequests(),
            'received' => $player->receivedPlayerRequests(),
            'accepted' => $player->friends(),
            default    => throw new \InvalidArgumentException('Invalid status provided.'),
        };

        // ---------------------------------------------
        // CASE A: status = 'accepted' AND we have a search
        //         => use your "exclude sent/received" approach
        // ---------------------------------------------
        if (!empty($search) && $status === 'accepted') {
            // Fresh query on the players table, excluding current user
            $filteredQuery = Player::where('id', '<>', $player->id)
                ->where('username', 'like', "%{$search}%");

            // Exclude players with a 'sent' or 'received' relationship
            $filteredQuery->whereNotIn('id', function ($sub) use ($player) {
                $sub->select('addressee_id')
                    ->from('friendships')
                    ->where('requester_id', $player->id)
                    ->whereIn('status', ['sent', 'received']);
            });

            $filteredQuery->whereNotIn('id', function ($sub) use ($player) {
                $sub->select('requester_id')
                    ->from('friendships')
                    ->where('addressee_id', $player->id)
                    ->whereIn('status', ['sent', 'received']);
            });

            // Paginate with preserved query params
            $results = $filteredQuery->paginate($perPage)
                ->withPath("/friendships/{$player->id}")
                ->appends([
                    'status' => $status,
                    'q'      => $search,
                ]);

            // ---------------------------------------------
            // CASE B: status = 'sent' or 'received' (or 'accepted' without a search)
            //         => filter directly on the base relationship if needed
            // ---------------------------------------------
        } else {
            // If there's a search, apply it to the relationship query
            // (belongsToMany -> you should be able to do ->where('username','like',...)
            //  If needed, use 'players.username' if there's any conflict)
            if (!empty($search)) {
                $relation->where('username', 'like', "%{$search}%");
            }

            // Finally paginate the relationship
            $results = $relation->paginate($perPage)
                ->withPath("/friendships/{$player->id}")
                ->appends([
                    'status' => $status,
                    'q'      => $search,
                ]);
        }

        $request->target_friend = $player->id;


        // Return JSON with self/next links pointing to the correct page & query params
        return response()->json([
            '_links' => [
                'self' => $results->url($results->currentPage()),
                'next' => $results->nextPageUrl(),
            ],
            'data' => PlayerResource::collection($results),
        ], 200);
    }






    /**
     * Store a new friend request in the database.
     * Example: The user identified by $player sends a friend request to another user.
     *
     * We assume there's a 'target_id' in the request to specify who they're friending.
     */
    public function store(Player $player, Request $request)
    {
        $request->validate([
            'target_id' => 'required|exists:players,id',
            'player_id' => 'required|exists:players,id',
        ]);

        $currentPlayer = Player::find($request->input('player_id'));

        if ($request->input('target_id') == $player->id) {
            return response()->json([
                'message' => 'You cannot send a friend request to yourself.'
            ], 400);
        }

        $targetId = $request->input('target_id');

        $existing = Friendship::where(function ($q) use ($player, $targetId) {
            // e.g. user is the requester
            $q->where('requester_id', $player->id)
                ->where('addressee_id', $targetId);
        })
            ->orWhere(function ($q) use ($player, $targetId) {
                // or user is the addressee
                $q->where('requester_id', $targetId)
                    ->where('addressee_id', $player->id);
            })
            ->first();

        if ($existing) {
            // Otherwise, if it's "accepted", "pending", etc., we just return 409 conflict
            return response()->json([
                'message' => 'A friendship or request already exists with that user.'
            ], 409);
        }

        $friendship = Friendship::create([
            'requester_id'  => $currentPlayer->id,
            'addressee_id'  => $targetId,
            'status'        => 'sent',
        ]);

        // 4) Return the newly created row or just a 201 response.
        return response()->json([
            'message' => 'Friend request created successfully.',
            'data' => $friendship, // if you want to return the new record
        ], 201);
    }


    /**
     * Update an existing friendship record (e.g. accept or reject).
     */
    public function update(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'target_id' => 'required|exists:players,id',
        ]);

        $friendship = Friendship::where('requester_id', $request->input('target_id'))
            ->where('addressee_id', $request->input('player_id'))
            ->first();

        $friendship->status = 'accepted';
        $friendship->save();

        return response()->json([], 200);
    }

    /**
     * Delete a friendship record.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'target_id' => 'required|exists:players,id',
        ]);

        $friendship = Friendship::where(function ($query) use ($request) {
            $query->where('requester_id', $request->input('player_id'))
                ->where('addressee_id', $request->input('target_id'));
        })
            ->orWhere(function ($query) use ($request) {
                $query->where('requester_id', $request->input('target_id'))
                    ->where('addressee_id', $request->input('player_id'));
            })
            ->first();


        if($friendship){
            $friendship->forceDelete();
        }

        return response()->json([], 204);
    }
}
