<?php

namespace App\Http\Resources;

use App\Models\Friendship;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

/**
 * @property-read Player $resource
 *
 * @mixin Player
 */
class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data=  [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'username' => $this->username,
            'zivas' => $this->zivas ?? 0,
            'is_member' => $this->is_member,
            'avatar' => $this->avatar ?: "https://images.unsplash.com/photo-1511367461989-f85a21fda167?q=80&w=1931&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
        ];

        if ($request->target_friend) {
            $data['is_friend'] = Friendship::where(function ($query) use ($request) {
                $query->where('requester_id', $this->id)
                    ->where('addressee_id', $request->target_friend)
                    ->where('status', 'accepted');
            })
                ->orWhere(function ($query) use ($request) {
                    $query->where('requester_id', $request->target_friend)
                        ->where('addressee_id', $this->id)
                        ->where('status', 'accepted');
                })
                ->exists();
        }

        return $data;
    }
}
