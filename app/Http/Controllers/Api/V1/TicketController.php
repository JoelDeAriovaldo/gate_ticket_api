<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\CreateTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Http\Resources\TicketCollection;
use App\Services\TicketService;
use App\Models\Ticket;
use Illuminate\Http\Request;

/**
 * TicketController
 *
 * Handles ticket management endpoints (CRUD + validation).
 */
class TicketController extends Controller
{
    protected TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * List tickets (paginated).
     */
    public function index(Request $request): TicketCollection
    {
        $tickets = Ticket::paginate($request->get('per_page', 15));
        return new TicketCollection($tickets);
    }

    /**
     * Store a new ticket.
     */
    public function store(CreateTicketRequest $request): \Illuminate\Http\JsonResponse
    {
        $ticket = $this->ticketService->createTicket($request->validated());

        return $this->successResponse(
            new TicketResource($ticket),
            'Ticket created successfully',
            201
        );
    }

    /**
     * Show a ticket.
     */
    public function show(Ticket $ticket)
    {
        return $this->successResponse(
            new TicketResource($ticket),
            'Ticket retrieved'
        );
    }

    /**
     * Update a ticket.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket = $this->ticketService->updateTicket($ticket, $request->validated());

        return $this->successResponse(
            new TicketResource($ticket),
            'Ticket updated successfully'
        );
    }

    /**
     * Delete a ticket.
     */
    public function destroy(Ticket $ticket)
    {
        $this->ticketService->deleteTicket($ticket);

        return $this->successResponse(null, 'Ticket deleted successfully');
    }

    /**
     * Validate a ticket (custom endpoint).
     */
    public function validateTicket(Ticket $ticket)
    {
        if ($ticket->isValid()) {
            return $this->successResponse(
                new TicketResource($ticket),
                'Ticket is valid'
            );
        }

        return $this->errorResponse('Ticket is not valid', 422);
    }
}
