<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * TicketService
 *
 * Handles ticket management business logic.
 * Used by TicketController.
 */
class TicketService
{
    /**
     * Get all tickets.
     *
     * @return Collection
     */
    public function getAllTickets(): Collection
    {
        return Ticket::all();
    }

    /**
     * Get tickets for a specific user.
     *
     * @param User $user
     * @return Collection
     */
    public function getTicketsByUser(User $user): Collection
    {
        return $user->tickets;
    }

    /**
     * Find a ticket by ID.
     *
     * @param int $id
     * @return Ticket|null
     */
    public function findTicketById(int $id): ?Ticket
    {
        return Ticket::find($id);
    }

    /**
     * Create a new ticket.
     *
     * @param array $data
     * @return Ticket
     */
    public function createTicket(array $data): Ticket
    {
        return Ticket::create($data);
    }

    /**
     * Update an existing ticket.
     *
     * @param Ticket $ticket
     * @param array $data
     * @return Ticket
     */
    public function updateTicket(Ticket $ticket, array $data): Ticket
    {
        $ticket->update($data);
        return $ticket;
    }

    /**
     * Delete a ticket.
     *
     * @param Ticket $ticket
     * @return void
     */
    public function deleteTicket(Ticket $ticket): void
    {
        $ticket->delete();
    }
}
