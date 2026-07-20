<?php

namespace App\Filament\Resources\Sales\Widgets;

use App\Services\ProfitService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SaleProfitOverview extends StatsOverviewWidget
{
    public ?object $record = null;

    protected function getStats(): array
    {
        if (! $this->record) {
            return [];
        }

        $profitService = app(ProfitService::class);

        $revenue = $profitService->revenue($this->record);
        $cost = $profitService->cost($this->record);
        $profit = $profitService->profit($this->record);
        $margin = $profitService->margin($this->record);

        return [

            Stat::make('Revenue', 'KES ' . number_format($revenue, 2))
                ->description('Total Sales Value')
                ->color('primary'),

            Stat::make('Cost', 'KES ' . number_format($cost, 2))
                ->description('Cost of Goods Sold')
                ->color('warning'),

            Stat::make('Gross Profit', 'KES ' . number_format($profit, 2))
                ->description('Revenue - Cost')
                ->color($profit >= 0 ? 'success' : 'danger'),

            Stat::make('Profit Margin', number_format($margin, 2) . '%')
                ->description('Gross Margin')
                ->color($margin >= 0 ? 'success' : 'danger'),

        ];
    }
}