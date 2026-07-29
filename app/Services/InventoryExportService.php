<?php

namespace App\Services;

use App\Models\Product;
use Spatie\SimpleExcel\SimpleExcelWriter;

class InventoryExportService
{
    public function export(array $filters): string
    {
        $file = storage_path('app/inventory-report.xlsx');

        $writer = SimpleExcelWriter::create($file);

        $query = app(InventoryReportService::class)
            ->query($filters)
            ->orderBy('name');

        $query->chunk(500, function ($products) use ($writer) {

            foreach ($products as $product) {

                $stockValue = $product->stock_quantity * $product->cost_price;

                $status = match (true) {

                    $product->stock_quantity == 0 => 'Out of Stock',

                    $product->stock_quantity <= $product->minimum_stock => 'Low Stock',

                    $product->stock_quantity > $product->maximum_stock => 'Overstocked',

                    default => 'In Stock',

                };

                $writer->addRow([

                    'Product' => $product->name,

                    'SKU' => $product->sku,

                    'Barcode' => $product->barcode,

                    'Category' => $product->category?->name,

                    'Warehouse' => $product->warehouse?->name,

                    'Supplier' => $product->supplier?->name,

                    'Cost Price' => $product->cost_price,

                    'Selling Price' => $product->selling_price,

                    'Current Stock' => $product->stock_quantity,

                    'Minimum Stock' => $product->minimum_stock,

                    'Maximum Stock' => $product->maximum_stock,

                    'Stock Value' => $stockValue,

                    'Status' => $status,

                ]);
            }

        });

        $writer->close();

        return $file;
    }
}