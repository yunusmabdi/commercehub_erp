<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CustomerReportService
{
    public function query(array $filters = []): Builder
    {
        return Customer::query()

            ->when(
                filled($filters['customer'] ?? null),
                fn (Builder $query) =>
                    $query->where('id', $filters['customer'])
            )

            ->when(
                filled($filters['from'] ?? null),
                fn (Builder $query) =>
                    $query->whereDate(
                        'created_at',
                        '>=',
                        $filters['from']
                    )
            )

            ->when(
                filled($filters['to'] ?? null),
                fn (Builder $query) =>
                    $query->whereDate(
                        'created_at',
                        '<=',
                        $filters['to']
                    )
            )

            ->latest('created_at');
    }

    public function get(array $filters = []): Collection
    {
        return $this->query($filters)->get();
    }
}