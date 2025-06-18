<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'truck_registration' => $this->truck_registration,
            'user_id' => $this->user_id,
            'valid_until' => $this->valid_until?->toISOString(),
            'access_gate' => $this->access_gate,
            'status' => $this->status,
            'used_at' => $this->used_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            // Computed properties
            'is_valid' => $this->isValid(),
            'is_expired' => $this->isExpired(),
            'is_used' => $this->status === 'used' || $this->used_at !== null,
            'time_remaining' => $this->getTimeRemaining(),
            'gate_display' => $this->getGateDisplay(),

            // Conditional user information
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'username' => $this->user->username,
                    'full_name' => $this->user->full_name,
                    'role' => $this->user->role,
                ];
            }),
        ];
    }

    /**
     * Get formatted time remaining until expiration.
     */
    protected function getTimeRemaining(): ?string
    {
        if (!$this->valid_until || $this->isExpired()) {
            return null;
        }

        $diff = now()->diff($this->valid_until);

        if ($diff->days > 0) {
            return "{$diff->days}d {$diff->h}h {$diff->i}m";
        } elseif ($diff->h > 0) {
            return "{$diff->h}h {$diff->i}m";
        } else {
            return "{$diff->i}m";
        }
    }

    /**
     * Get display name for the access gate.
     */
    protected function getGateDisplay(): string
    {
        if ($this->access_gate instanceof \App\Enums\AccessGate) {
            return $this->access_gate->label();
        }
        // Fallback for string values
        return match($this->access_gate) {
            'gate_4' => 'Gate 4',
            'gate_5' => 'Gate 5',
            'gate_6' => 'Gate 6',
            'gate_8' => 'Gate 8',
            'gate_11' => 'Gate 11',
            'gate_16' => 'Gate 16',
            default => (string) $this->access_gate,
        };
    }
}
