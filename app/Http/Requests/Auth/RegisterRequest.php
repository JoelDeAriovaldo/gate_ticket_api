<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserRole;

/**
 * RegisterRequest
 *
 * Handles validation for user registration.
 * Used by AuthController::register.
 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'   => ['required', 'string', 'unique:users,username'],
            'full_name'  => ['required', 'string', 'max:255'],
            'email'      => ['nullable', 'email', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'role'       => ['sometimes', 'in:' . implode(',', array_column(UserRole::cases(), 'value'))],
            'is_active'  => ['sometimes', 'boolean'],
        ];
    }
}
