<?php

namespace App\Console\Commands;

use App\Services\TicketService;
use Illuminate\Console\Command;

class ExpireTicketsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tickets:expire
                          {--dry-run : Show what would be expired without making changes}';

    /**
     * The console command description.
     */
    protected $description = 'Expire tickets that are past their valid_until date';

    /**
     * Create a new command instance.
     */
    public function __construct(protected TicketService $ticketService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting ticket expiration process...');

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        try {
            if ($this->option('dry-run')) {
                // Show what would be expired
                $expiredTickets = \App\Models\Ticket::where('valid_until', '<', now())
                    ->where('status', 'active')
                    ->get();

                if ($expiredTickets->isEmpty()) {
                    $this->info('No tickets to expire.');
                    return self::SUCCESS;
                }

                $this->info("Found {$expiredTickets->count()} tickets that would be expired:");

                $headers = ['ID', 'Ticket Number', 'Truck Registration', 'Valid Until', 'Status'];
                $rows = $expiredTickets->map(function ($ticket) {
                    return [
                        $ticket->id,
                        $ticket->ticket_number,
                        $ticket->truck_registration,
                        $ticket->valid_until->format('Y-m-d H:i:s'),
                        $ticket->status,
                    ];
                });

                $this->table($headers, $rows);
            } else {
                // Actually expire the tickets
                $expiredCount = $this->ticketService->expireOldTickets();

                if ($expiredCount === 0) {
                    $this->info('No tickets to expire.');
                } else {
                    $this->info("Successfully expired {$expiredCount} tickets.");
                }
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to expire tickets: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
