<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\AccessGate;

/**
 * CreateTicketRequest
 *
 * Handles validation for creating a ticket.
 * Used by TicketController::store.
 */
class CreateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'truck_registration' => ['required', 'string', 'max:255'],
            'user_id'            => ['required', 'exists:users,id'],
            'valid_until'        => ['required', 'date', 'after:now'],
            'access_gate'        => ['required', 'in:' . implode(',', array_column(AccessGate::cases(), 'value'))],
            'status'             => ['sometimes', 'in:active,used,expired,cancelled'],
        ];
    }
}
