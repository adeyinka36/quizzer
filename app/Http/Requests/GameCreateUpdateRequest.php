<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameCreateUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|unique:games,name|max:65535',
            'start_date_time' => 'required|date',
            'creator_id' => 'nullable|uuid|exists:players,id',
            'topic_id' => 'nullable|uuid|exists:topics,id',
            ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Game name cannot exceed 255 characters.',
            'start_date_time' => 'Start time must be a valid start date time.',
            'creator_id.uuid' => 'Creator ID must be a valid creator UUID.',
            'topic_id.uuid' => 'Topic ID must be a valid topic UUID.',
        ];
    }
}
