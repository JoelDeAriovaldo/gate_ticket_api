<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'truck_registration' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Z0-9\-]+$/', // Only alphanumeric and hyphens, uppercase
            ],
            'user_id' => [
                'sometimes', // Optional since we'll use auth()->id()
                'integer',
                'exists:users,id'
            ],
            'valid_until' => [
                'required',
                'date',
                'after:now'
            ],
            'access_gate' => [
                'required',
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
                'sometimes', // Optional, defaults to 'active'
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
            'truck_registration.required' => 'Truck registration is required.',
            'truck_registration.regex' => 'Truck registration must contain only letters, numbers, and hyphens.',
            'valid_until.required' => 'Valid until date is required.',
            'valid_until.after' => 'Valid until must be a future date.',
            'access_gate.required' => 'Access gate is required.',
            'access_gate.in' => 'The selected access gate is invalid. Valid options: gate_4, gate_5, gate_6, gate_8, gate_11, gate_16.',
            'user_id.exists' => 'The selected user does not exist.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert truck registration to uppercase
        if ($this->has('truck_registration')) {
            $this->merge([
                'truck_registration' => strtoupper($this->truck_registration)
            ]);
        }

        // Set default status if not provided
        if (!$this->has('status')) {
            $this->merge([
                'status' => 'active'
            ]);
        }
    }

    /**
     * Get validated data with defaults.
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        // Ensure user_id is set to authenticated user
        $validated['user_id'] = auth()->id();

        // Ensure status is set
        if (!isset($validated['status'])) {
            $validated['status'] = 'active';
        }

        return $validated;
    }
}
