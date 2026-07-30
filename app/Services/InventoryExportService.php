<?php

namespace App\Services;

use Spatie\SimpleExcel\SimpleExcelWriter;

class InventoryExportService
{
    public function export(array $filters): string
    {
        $file = storage_path('app/inventory-report.xlsx');

        $writer = SimpleExcelWriter::create($file);

        $reportService = app(InventoryReportService::class);

        $query = $reportService
            ->query($filters)
            ->orderBy('name');

        foreach ($query->cursor() as $product) {

            $stockValue = $product->stock_quantity * $product->cost_price;

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

                'Status' => $reportService->stockStatus($product),

            ]);
        }

        $writer->close();

        return $file;
    }
}