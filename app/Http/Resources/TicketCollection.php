<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TicketCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = TicketResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
                'has_more' => $this->hasMorePages(),
            ],
            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
                'current' => $this->url($this->currentPage()),
            ],
            'statistics' => $this->when($request->get('include_stats'), function () {
                return $this->getStatistics();
            }),
        ];
    }

    /**
     * Get collection statistics.
     */
    protected function getStatistics(): array
    {
        $items = $this->collection;

        $total = $items->count();
        $active = $items->where('status', 'active')
            ->where('valid_until', '>', now())
            ->count();
        $expired = $items->where('valid_until', '<', now())->count();
        $used = $items->where('status', 'used')->count();

        return [
            'total' => $total,
            'active' => $active,
            'expired' => $expired,
            'used' => $used,
            'inactive' => $total - $active - $expired - $used,
        ];
    }

    /**
     * Additional metadata for the response.
     */
    public function with(Request $request): array
    {
        return [
            'success' => true,
            'message' => 'Tickets retrieved successfully',
        ];
    }
}
