<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && $this->route('ticket')->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'truck_registration' => [
                'sometimes',
                'string',
                'max:255',
                'regex:/^[A-Z0-9\-]+$/', // Only alphanumeric and hyphens, uppercase
            ],
            'valid_until' => [
                'sometimes',
                'date',
                'after:now'
            ],
            'access_gate' => [
                'sometimes',
                'string',
                Rule::in([
                    'gate_4',
                    'gate_5',
                    'gate_6',
                    'gate_8',
                    'gate_11',
                    'gate_16'
                ])
            ],
            'status' => [
                'sometimes',
                'string',
                Rule::in(['active', 'expired', 'used'])
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'truck_registration.regex' => 'Truck registration must contain only letters, numbers, and hyphens.',
            'valid_until.after' => 'Valid until must be a future date.',
            'access_gate.in' => 'The selected access gate is invalid. Valid options: gate_4, gate_5, gate_6, gate_8, gate_11, gate_16.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert truck registration to uppercase if provided
        if ($this->has('truck_registration')) {
            $this->merge([
                'truck_registration' => strtoupper($this->truck_registration)
            ]);
        }
    }
}
