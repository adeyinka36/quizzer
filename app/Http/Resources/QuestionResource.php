<?php

namespace App\Http\Resources;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Question $resource
 *
 * @mixin Question
 *
 * @property Collection<int, Option> $options
 */
class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'question' => $this->question_text,
            'options' => OptionResource::collection($this->options),
        ];
    }
}
