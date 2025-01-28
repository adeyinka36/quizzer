<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'start_date_time' => $this->start_date_time,
            'winner_id' => $this->winner_id,
            'status' => $this->status,
            'creator_id' => $this->creator_id,
        ];
    }
}
