<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ProfitExportService
{
    public function __construct(
        protected ProfitReportService $reportService,
    ) {}

    public function exportPdf(array $filters)
    {
        return Pdf::loadView(
            'reports.profit-report-pdf',
            [
                'items'  => $this->reportService->get($filters),
                'totals' => $this->reportService->totals($filters),
            ]
        );
    }

    public function exportExcel(array $filters): string
    {
        $file = storage_path('app/profit-report.xlsx');

        $writer = SimpleExcelWriter::create($file);

        $this->reportService
            ->query($filters)
            ->chunk(500, function ($items) use ($writer) {

                foreach ($items as $item) {

                    $revenue = $item->quantity * $item->unit_price;

                    $cost = $item->quantity * $item->cost_price;

                    $profit = $revenue - $cost;

                    $writer->addRow([

                        'Invoice' => $item->sale->invoice_number,

                        'Date' => $item->sale->sale_date,

                        'Customer' =>
                            $item->sale->customer?->name
                            ?? 'Walk-in Customer',

                        'Product' => $item->product?->name,

                        'Quantity' => $item->quantity,

                        'Unit Price' => $item->unit_price,

                        'Cost Price' => $item->cost_price,

                        'Revenue' => $revenue,

                        'Cost' => $cost,

                        'Profit' => $profit,

                    ]);
                }
            });

        return $file;
    }
}