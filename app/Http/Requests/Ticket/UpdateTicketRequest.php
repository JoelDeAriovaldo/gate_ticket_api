<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\AccessGate;

/**
 * UpdateTicketRequest
 *
 * Handles validation for updating a ticket.
 * Used by TicketController::update.
 */
class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'truck_registration' => ['sometimes', 'string', 'max:255'],
            'user_id'            => ['sometimes', 'exists:users,id'],
            'valid_until'        => ['sometimes', 'date', 'after:now'],
            'access_gate'        => ['sometimes', 'in:' . implode(',', array_column(AccessGate::cases(), 'value'))],
            'status'             => ['sometimes', 'in:active,used,expired,cancelled'],
            'used_at'            => ['nullable', 'date'],
        ];
    }
}
