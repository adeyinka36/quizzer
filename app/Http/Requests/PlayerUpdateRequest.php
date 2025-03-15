<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class PlayerUpdateRequest extends FormRequest
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
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:players,email,'.$this->player->id],
            'username' => ['nullable', 'string', 'max:255', 'unique:players,username,'.$this->player->id],
            'new_password' => [
                'nullable',
                'string',
                'min:8', // Increase minimum length to at least 8 characters
                'regex:/[A-Z]/', // Require at least one uppercase letter
                'regex:/[a-z]/', // Require at least one lowercase letter
                'regex:/[0-9]/', // Require at least one number
                'regex:/[@$!%*?&#]/', // Require at least one special character
            ],
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!Hash::check($value, $this->player->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'new_password_confirmation' => ['required_with:new_password', 'same:new_password'],
        ];
    }
}
