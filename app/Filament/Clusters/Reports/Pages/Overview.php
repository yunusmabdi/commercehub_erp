<?php

namespace App\Filament\Clusters\Reports\Pages;

use App\Filament\Clusters\Reports\ReportsCluster;
use App\Filament\Widgets\LowStockProducts;
use App\Filament\Widgets\SalesChart;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Page;
use Filament\Widgets\Widget;

class Overview extends Page
{
    protected string $view = 'filament.clusters.reports.pages.overview';

    protected static ?string $cluster = ReportsCluster::class;

    protected static ?string $title = 'Reports Overview';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-pie';

    public function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
            SalesChart::class,
            LowStockProducts::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return [
            'md' => 2,
            'xl' => 2,
        ];
    }
}