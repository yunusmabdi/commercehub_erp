<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use UnitEnum;

class ProfitReport extends Page
{
    protected string $view = 'filament.pages.profit-report';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Profit Report';

    protected static ?string $title = 'Profit Report';

    protected static ?int $navigationSort = 5;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';
}
