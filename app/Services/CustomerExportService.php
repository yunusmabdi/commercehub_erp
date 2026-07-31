<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\SimpleExcel\SimpleExcelWriter;

class CustomerExportService
{
    public function __construct(
        protected CustomerReportService $reportService,
    ) {}

    public function exportPdf(array $filters)
    {
        $customers = $this->reportService->get($filters);

        return Pdf::loadView(
            'reports.customer-report-pdf',
            [
                'customers' => $customers,
            ]
        );
    }

    public function exportExcel(array $filters): string
    {
        $file = storage_path('app/customer-report.xlsx');

        $writer = SimpleExcelWriter::create($file);

        $this->reportService
            ->query($filters)
            ->chunk(500, function ($customers) use ($writer) {

                foreach ($customers as $customer) {

                    $writer->addRow([
                        'Customer Code' => $customer->customer_code,
                        'Name'          => $customer->name,
                        'Phone'         => $customer->phone,
                        'Email'         => $customer->email,
                        'Address'       => $customer->address,
                        'Created At'    => optional($customer->created_at)->format('Y-m-d'),
                    ]);
                }
            });

        return $file;
    }
}