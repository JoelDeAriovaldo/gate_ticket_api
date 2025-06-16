<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create tickets table
 *
 * Stores ticket information for access control.
 * References users table via foreign key.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('truck_registration');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('valid_until');
            $table->enum('access_gate', [
                'gate_11a', 'gate_4', 'gate_5', 'gate_6', 'gate_8a', 'gate_16'
            ])->default('gate_16');
            $table->enum('status', ['active', 'used', 'expired', 'cancelled'])->default('active');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['truck_registration', 'status']);
            $table->index(['valid_until', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
