<?php

namespace App\Services;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PurchaseReportService
{
    /**
     * Build the purchase report query.
     */
    public function query(array $filters): Builder
    {
        return Purchase::query()
            ->with('supplier')

            ->when(
                filled($filters['from'] ?? null),
                fn (Builder $query) =>
                $query->whereDate(
                    'purchase_date',
                    '>=',
                    $filters['from']
                )
            )

            ->when(
                filled($filters['to'] ?? null),
                fn (Builder $query) =>
                $query->whereDate(
                    'purchase_date',
                    '<=',
                    $filters['to']
                )
            )

            ->when(
                filled($filters['supplier'] ?? null),
                fn (Builder $query) =>
                $query->where(
                    'supplier_id',
                    $filters['supplier']
                )
            )

            ->when(
                filled($filters['status'] ?? null),
                fn (Builder $query) =>
                $query->where(
                    'status',
                    $filters['status']
                )
            )

            ->latest('purchase_date');
    }

    /**
     * Return the filtered purchases.
     */
    public function get(array $filters): Collection
    {
        return $this->query($filters)->get();
    }
}