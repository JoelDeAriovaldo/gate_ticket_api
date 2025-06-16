<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserRole;

/**
 * UpdateUserRequest
 *
 * Handles validation for updating a user.
 * Used by UserController::update.
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id ?? null;

        return [
            'username'   => ['sometimes', 'string', 'unique:users,username,' . $userId],
            'full_name'  => ['sometimes', 'string', 'max:255'],
            'email'      => ['nullable', 'email', 'unique:users,email,' . $userId],
            'password'   => ['nullable', 'string', 'min:8'],
            'role'       => ['sometimes', 'in:' . implode(',', array_column(UserRole::cases(), 'value'))],
            'is_active'  => ['sometimes', 'boolean'],
        ];
    }
}
