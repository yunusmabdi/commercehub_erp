<?php

namespace App\Services;

use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;

class InventoryReportService
{
    /**
     * Build the filtered inventory query.
     */
    public function query(array $filters): Builder
    {
        return Product::query()
            ->with([
                'category',
                'supplier',
                'warehouse',
            ])

            ->when(
                filled($filters['warehouse'] ?? null),
                fn (Builder $query) => $query->where(
                    'warehouse_id',
                    $filters['warehouse']
                )
            )

            ->when(
                filled($filters['category'] ?? null),
                fn (Builder $query) => $query->where(
                    'category_id',
                    $filters['category']
                )
            )

            ->when(
                filled($filters['supplier'] ?? null),
                fn (Builder $query) => $query->where(
                    'supplier_id',
                    $filters['supplier']
                )
            )

            ->when(
                filled($filters['product'] ?? null),
                fn (Builder $query) => $query->where(
                    'name',
                    'like',
                    '%' . trim($filters['product']) . '%'
                )
            )

            ->when(
                filled($filters['sku'] ?? null),
                fn (Builder $query) => $query->where(
                    'sku',
                    'like',
                    '%' . trim($filters['sku']) . '%'
                )
            )

            ->when(
                filled($filters['status'] ?? null),
                function (Builder $query) use ($filters) {

                    switch ($filters['status']) {

                        case 'in_stock':
                            $query->where('stock_quantity', '>', 0)
                                ->whereColumn('stock_quantity', '>', 'minimum_stock')
                                ->whereColumn('stock_quantity', '<=', 'maximum_stock');
                            break;

                        case 'low_stock':
                            $query->where('stock_quantity', '>', 0)
                                ->whereColumn('stock_quantity', '<=', 'minimum_stock');
                            break;

                        case 'out_of_stock':
                            $query->where('stock_quantity', 0);
                            break;

                        case 'overstocked':
                            $query->whereColumn('stock_quantity', '>', 'maximum_stock');
                            break;
                    }
                });
    }

    /**
     * Return the stock status label.
     */
    public function stockStatus(Product $product): string
    {
        if ($product->stock_quantity == 0) {
            return 'Out of Stock';
        }

        if ($product->stock_quantity <= $product->minimum_stock) {
            return 'Low Stock';
        }

        if ($product->stock_quantity > $product->maximum_stock) {
            return 'Overstocked';
        }

        return 'In Stock';
    }

    /**
     * Summary cards.
     */
    public function summary(array $filters): array
    {
        $products = $this->query($filters)->get();

        return [

            'total_products' => $products->count(),

            'total_quantity' => $products->sum('stock_quantity'),

            'total_value' => $products->sum(
                fn (Product $product) => $product->stock_quantity * $product->cost_price
            ),

            'low_stock' => $products->filter(
                fn (Product $product) =>
                    $product->stock_quantity > 0 &&
                    $product->stock_quantity <= $product->minimum_stock
            )->count(),

            'out_of_stock' => $products->where('stock_quantity', 0)->count(),

        ];
    }

    /**
     * Generate PDF.
     */
    public function generatePdf(array $filters)
    {
        $products = $this->query($filters)
            ->orderBy('name')
            ->get();

        return Pdf::loadView(
            'reports.inventory-report-pdf',
            [
                'products' => $products,
                'summary' => $this->summary($filters),
            ]
        );
    }
}