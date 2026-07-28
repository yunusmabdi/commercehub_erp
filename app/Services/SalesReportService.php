<?php

namespace App\Services;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesReportService
{

    public function generatePdf(array $filters)
    {

        $sales = Sale::query()
            ->with('customer')

            ->when(
                filled($filters['from'] ?? null),
                fn ($query) =>
                    $query->whereDate(
                        'sale_date',
                        '>=',
                        $filters['from']
                    )
            )

            ->when(
                filled($filters['to'] ?? null),
                fn ($query) =>
                    $query->whereDate(
                        'sale_date',
                        '<=',
                        $filters['to']
                    )
            )

            ->when(
                filled($filters['status'] ?? null),
                fn ($query) =>
                    $query->where(
                        'status',
                        $filters['status']
                    )
            )

            ->when(
                filled($filters['customer'] ?? null),
                fn ($query) =>
                    $query->where(
                        'customer_id',
                        $filters['customer']
                    )
            )

            ->latest('sale_date')
            ->get();


        return Pdf::loadView(
            'reports.sales-report-pdf',
            [
                'sales' => $sales
            ]
        );

    }

}