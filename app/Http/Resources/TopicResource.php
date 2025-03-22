<?php

namespace App\Http\Resources;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Topic $resource
 *
 * @mixin Topic
 */
class TopicResource extends JsonResource
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
            'description' => $this->description,
            'image' => $this->image,
            'is_custom' => $this->is_custom,
            'title' => $this->title,
        ];
    }
}
