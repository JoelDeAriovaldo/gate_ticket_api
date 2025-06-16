<?php

namespace App\Enums;

/**
 * Enum AccessGate
 *
 * Defines the available access gates for tickets.
 * Used for type-safe gate management and validation.
 */
enum AccessGate: string
{
    case GATE_11A = 'gate_11a';
    case GATE_4 = 'gate_4';
    case GATE_5 = 'gate_5';
    case GATE_6 = 'gate_6';
    case GATE_8A = 'gate_8a';
    case GATE_16 = 'gate_16';

    /**
     * Get a human-readable label for the gate.
     */
    public function label(): string
    {
        return match($this) {
            self::GATE_11A => 'Gate 11A',
            self::GATE_4 => 'Gate 4',
            self::GATE_5 => 'Gate 5',
            self::GATE_6 => 'Gate 6',
            self::GATE_8A => 'Gate 8A',
            self::GATE_16 => 'Gate 16',
        };
    }
}
