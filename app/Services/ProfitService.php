<?php

namespace App\Services;

use App\Models\Sale;

class ProfitService
{
    public function revenue(Sale $sale): float
    {
        return (float) $sale->items->sum('line_total');
    }

    public function cost(Sale $sale): float
    {
        return (float) $sale->items->sum(function ($item) {
            return $item->quantity * $item->cost_price;
        });
    }

    public function profit(Sale $sale): float
    {
        return $this->revenue($sale) - $this->cost($sale);
    }

    public function margin(Sale $sale): float
    {
        $revenue = $this->revenue($sale);

        if ($revenue <= 0) {
            return 0;
        }

        return round(
            ($this->profit($sale) / $revenue) * 100,
            2
        );
    }
}