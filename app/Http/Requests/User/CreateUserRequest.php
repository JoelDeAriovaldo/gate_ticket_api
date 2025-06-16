<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserRole;

/**
 * CreateUserRequest
 *
 * Handles validation for creating a user.
 * Used by UserController::store.
 */
class CreateUserRequest extends FormRequest
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
            'password'   => ['required', 'string', 'min:8'],
            'role'       => ['required', 'in:' . implode(',', array_column(UserRole::cases(), 'value'))],
            'is_active'  => ['sometimes', 'boolean'],
        ];
    }
}
