<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\SimpleExcel\SimpleExcelWriter;

class PurchaseExportService
{
    public function __construct(
        protected PurchaseReportService $reportService
    ) {}

    /**
     * Export Purchase Report as PDF.
     */
    public function exportPdf(array $filters)
    {
        $purchases = $this->reportService
            ->get($filters);

        return Pdf::loadView(
            'reports.purchase-report-pdf',
            [
                'purchases' => $purchases,
            ]
        );
    }

    /**
     * Export Purchase Report as Excel.
     */
    public function exportExcel(array $filters): string
    {
        $file = storage_path('app/purchase-report.xlsx');

        $writer = SimpleExcelWriter::create($file);

        $this->reportService
            ->query($filters)
            ->chunk(500, function ($purchases) use ($writer) {

                foreach ($purchases as $purchase) {

                    $writer->addRow([
                        'Purchase Number' => $purchase->purchase_number,

                        'Supplier' =>
                            $purchase->supplier?->company_name
                            ?? 'N/A',

                        'Purchase Date' =>
                            $purchase->purchase_date,

                        'Status' =>
                            $purchase->status,

                        'Total Amount' =>
                            $purchase->total_amount,
                    ]);
                }
            });

        return $file;
    }
}