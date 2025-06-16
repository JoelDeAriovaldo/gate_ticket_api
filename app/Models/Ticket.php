<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\AccessGate;

/**
 * Ticket Model
 *
 * Represents a ticket entity with validation and business logic.
 * Relationships: Belongs to a user.
 */
class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'truck_registration',
        'user_id',
        'valid_until',
        'access_gate',
        'status',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'valid_until' => 'datetime',
            'used_at' => 'datetime',
            'access_gate' => AccessGate::class,
        ];
    }

    /**
     * Get the user that owns the ticket.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the ticket is valid.
     */
    public function isValid(): bool
    {
        return $this->status === 'active' && $this->valid_until > now();
    }

    /**
     * Check if the ticket is expired.
     */
    public function isExpired(): bool
    {
        return $this->valid_until < now();
    }

    /**
     * Mark the ticket as used.
     */
    public function markAsUsed(): void
    {
        $this->update([
            'status' => 'used',
            'used_at' => now(),
        ]);
    }

    /**
     * Boot method to auto-generate ticket number.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $date = now()->format('Ymd');
            $count = self::whereDate('created_at', now()->toDateString())->count() + 1;
            $ticket->ticket_number = sprintf('%s-%03d', $date, $count);
        });
    }
}
