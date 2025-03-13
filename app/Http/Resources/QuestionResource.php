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
            'id' => $this->id,
            'question_text' => $this->question_text,
            'options' => $this->options,
            'answer' => $this->answer,
            'topic_id' => $this->topic_id,
        ];
    }
}
