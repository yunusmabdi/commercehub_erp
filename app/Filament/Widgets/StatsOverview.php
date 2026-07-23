<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        $todayRevenue = Sale::whereDate('sale_date', today())
            ->where('status', 'Completed')
            ->sum('total_amount');

        $todaySales = Sale::whereDate('sale_date', today())
            ->where('status', 'Completed')
            ->count();

        $customers = Customer::count();

        $lowStock = Product::whereColumn('stock_quantity', '<=', 'minimum_stock')
            ->count();

        return [

            Stat::make(
                'Today\'s Revenue',
                'KES ' . number_format($todayRevenue, 2)
            )
                ->description('Completed sales today')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make(
                'Today\'s Sales',
                $todaySales
            )
                ->description('Completed transactions')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),

            Stat::make(
                'Customers',
                $customers
            )
                ->description('Registered customers')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make(
                'Low Stock',
                $lowStock
            )
                ->description('Products needing restock')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

        ];
    }
}