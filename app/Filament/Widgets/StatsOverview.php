<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make('Products', Product::count())
                ->description('Total Products')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make('Categories', Category::count())
                ->description('Total Categories')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info'),

            Stat::make('Suppliers', Supplier::count())
                ->description('Total Suppliers')
                ->descriptionIcon('heroicon-m-truck')
                ->color('warning'),

        ];
    }
}