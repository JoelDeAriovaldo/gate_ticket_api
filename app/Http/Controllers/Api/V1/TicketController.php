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
use Illuminate\Http\JsonResponse;

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

        // Apply auth middleware to all methods
        $this->middleware('auth:sanctum');
    }

    /**
     * List tickets (paginated).
     */
    public function index(Request $request)
    {
        try {
            // Get tickets for the authenticated user
            $tickets = Ticket::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));

            return $this->successResponse(
                new TicketCollection($tickets),
                'Tickets retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve tickets: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store a new ticket.
     */
    public function store(CreateTicketRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            // Ensure the ticket belongs to the authenticated user
            $validatedData['user_id'] = auth()->id();

            $ticket = $this->ticketService->createTicket($validatedData);

            return $this->successResponse(
                new TicketResource($ticket->fresh()), // Refresh to get auto-generated fields
                'Ticket created successfully',
                201
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse(
                'Validation failed',
                422,
                $e->errors()
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create ticket: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Show a ticket.
     */
    public function show(Ticket $ticket)
    {
        try {
            // Check if user owns this ticket
            if ($ticket->user_id !== auth()->id()) {
                return $this->errorResponse(
                    'Unauthorized access to ticket',
                    403
                );
            }

            return $this->successResponse(
                new TicketResource($ticket->load('user')),
                'Ticket retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve ticket: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Update a ticket.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        try {
            // Check if user owns this ticket
            if ($ticket->user_id !== auth()->id()) {
                return $this->errorResponse(
                    'Unauthorized access to ticket',
                    403
                );
            }

            $ticket = $this->ticketService->updateTicket($ticket, $request->validated());

            return $this->successResponse(
                new TicketResource($ticket->fresh()),
                'Ticket updated successfully'
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse(
                'Validation failed',
                422,
                $e->errors()
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update ticket: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Delete a ticket.
     */
    public function destroy(Ticket $ticket)
    {
        try {
            // Check if user owns this ticket
            if ($ticket->user_id !== auth()->id()) {
                return $this->errorResponse(
                    'Unauthorized access to ticket',
                    403
                );
            }

            $this->ticketService->deleteTicket($ticket);

            return $this->successResponse(
                null,
                'Ticket deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete ticket: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Validate a ticket (custom endpoint).
     * This marks the ticket as used when validated.
     */
    public function validateTicket(Ticket $ticket)
    {
        try {
            // Check if user owns this ticket
            if ($ticket->user_id !== auth()->id()) {
                return $this->errorResponse(
                    'Unauthorized access to ticket',
                    403
                );
            }

            // Check if ticket is valid (active and not expired)
            if (!$ticket->isValid()) {
                $reason = $ticket->isExpired() ? 'expired' : 'inactive';
                return $this->errorResponse(
                    "Ticket is not valid (status: {$reason})",
                    422,
                    ['ticket' => new TicketResource($ticket)]
                );
            }

            // Check if ticket is already used
            if ($ticket->status === 'used' || $ticket->used_at !== null) {
                return $this->errorResponse(
                    'Ticket has already been used',
                    422,
                    ['ticket' => new TicketResource($ticket)]
                );
            }

            // Mark ticket as used
            $ticket = $this->ticketService->validateTicket($ticket);

            return $this->successResponse(
                new TicketResource($ticket->fresh()),
                'Ticket validated and marked as used successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to validate ticket: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get ticket statistics for the authenticated user.
     */
    public function statistics()
    {
        try {
            $userId = auth()->id();

            $stats = [
                'total' => Ticket::where('user_id', $userId)->count(),
                'active' => Ticket::where('user_id', $userId)
                    ->where('status', 'active')
                    ->where('valid_until', '>', now())
                    ->count(),
                'expired' => Ticket::where('user_id', $userId)
                    ->where('valid_until', '<', now())
                    ->count(),
                'used' => Ticket::where('user_id', $userId)
                    ->where('status', 'used')
                    ->count(),
            ];

            return $this->successResponse(
                $stats,
                'Statistics retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve statistics: ' . $e->getMessage(),
                500
            );
        }
    }
}
