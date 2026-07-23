<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    protected ?string $heading = 'Sales Trend (Last 7 Days)';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $labels = [];
        $revenue = [];

        foreach (range(6, 0) as $day) {

            $date = now()->subDays($day);

            $labels[] = $date->format('D');

            $revenue[] = Sale::query()
                ->where('status', 'Completed')
                ->whereDate('sale_date', $date)
                ->sum('total_amount');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (KES)',
                    'data' => $revenue,
                ],
            ],

            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}