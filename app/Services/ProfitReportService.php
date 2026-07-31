<?php

namespace App\Services;

use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProfitReportService
{
    public function query(array $filters = []): Builder
    {
        return SaleItem::query()

            ->with([
                'sale.customer',
                'product',
            ])

            ->whereHas('sale', function (Builder $query) use ($filters) {

                $query

                    ->when(
                        filled($filters['from'] ?? null),
                        fn (Builder $query) =>
                            $query->whereDate(
                                'sale_date',
                                '>=',
                                $filters['from']
                            )
                    )

                    ->when(
                        filled($filters['to'] ?? null),
                        fn (Builder $query) =>
                            $query->whereDate(
                                'sale_date',
                                '<=',
                                $filters['to']
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

                    ->when(
                        filled($filters['customer'] ?? null),
                        fn (Builder $query) =>
                            $query->where(
                                'customer_id',
                                $filters['customer']
                            )
                    );

            })

            ->when(
                filled($filters['product'] ?? null),
                fn (Builder $query) =>
                    $query->where(
                        'product_id',
                        $filters['product']
                    )
            );
    }

    public function get(array $filters = []): Collection
    {
        return $this->query($filters)->get();
    }

    public function totals(array $filters = []): array
    {
        $totals = $this->query($filters)
            ->selectRaw('
                SUM(quantity * unit_price) as revenue,
                SUM(quantity * cost_price) as cost
            ')
            ->first();

        $revenue = (float) ($totals->revenue ?? 0);

        $cost = (float) ($totals->cost ?? 0);

        $profit = $revenue - $cost;

        return [

            'revenue' => $revenue,

            'cost' => $cost,

            'profit' => $profit,

            'margin' => $revenue > 0
                ? round(($profit / $revenue) * 100, 2)
                : 0,

        ];
    }
}