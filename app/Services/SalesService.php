<?php

namespace App\Services;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class SalesService
{
    public function __construct(
        protected InventoryService $inventoryService,
    ) {
    }

    /**
     * Complete a sale.
     */
    public function completeSale(Sale $sale): void
    {
        DB::transaction(function () use ($sale) {

            $this->inventoryService->validateSaleStock($sale);

            $this->inventoryService->issueSaleStock($sale);

            $sale->update([
                'status' => 'Completed',
            ]);
        });
    }
}