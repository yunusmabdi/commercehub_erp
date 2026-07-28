<?php

namespace App\Services;

use App\Models\Sale;
use Spatie\SimpleExcel\SimpleExcelWriter;

class SalesExportService
{
    public function export(array $filters)
    {
        $file = storage_path('app/sales-report.xlsx');

        $writer = SimpleExcelWriter::create($file);

        Sale::query()
            ->with('customer')
            ->when(
                filled($filters['from'] ?? null),
                fn($query) =>
                    $query->whereDate(
                        'sale_date',
                        '>=',
                        $filters['from']
                    )
            )
            ->when(
                filled($filters['to'] ?? null),
                fn($query) =>
                    $query->whereDate(
                        'sale_date',
                        '<=',
                        $filters['to']
                    )
            )
            ->when(
                filled($filters['status'] ?? null),
                fn($query) =>
                    $query->where(
                        'status',
                        $filters['status']
                    )
            )
            ->when(
                filled($filters['customer'] ?? null),
                fn($query) =>
                    $query->where(
                        'customer_id',
                        $filters['customer']
                    )
            )
            ->chunk(500, function ($sales) use ($writer) {

                foreach ($sales as $sale) {

                    $writer->addRow([
                        'Invoice' => $sale->invoice_number,

                        'Customer' =>
                            $sale->customer?->name
                            ?? 'Walk-in Customer',

                        'Status' => $sale->status,

                        'Date' => $sale->sale_date,

                        'Subtotal' => $sale->subtotal,

                        'Tax' => $sale->tax,

                        'Discount' => $sale->discount,

                        'Total' => $sale->total_amount,
                    ]);
                }

            });

        return $file;
    }
}