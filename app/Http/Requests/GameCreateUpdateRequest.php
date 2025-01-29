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
            'monetization_id' => 'nullable|uuid|exists:monetizations,id',
            'creator_id' => 'nullable|uuid|exists:players,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Game name cannot exceed 255 characters.',
            'start_time.required' => 'Start time is required.',
            'start_time.date' => 'Start time must be a valid date.',
            'monetization_id.uuid' => 'Monetization ID must be a valid UUID.',
            'creator_id.uuid' => 'Creator ID must be a valid UUID.',
        ];
    }
}
