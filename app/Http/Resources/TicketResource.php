<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * TicketResource
 *
 * Transforms ticket data for API responses.
 * Used by controllers returning ticket data.
 */
class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'ticket_number'     => $this->ticket_number,
            'truck_registration'=> $this->truck_registration,
            'user_id'           => $this->user_id,
            'valid_until'       => $this->valid_until?->toIso8601String(),
            'access_gate'       => $this->access_gate->value ?? $this->access_gate,
            'status'            => $this->status,
            'used_at'           => $this->used_at?->toIso8601String(),
            'created_at'        => $this->created_at?->toIso8601String(),
            'updated_at'        => $this->updated_at?->toIso8601String(),
        ];
    }
}
