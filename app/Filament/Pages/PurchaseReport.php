<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use UnitEnum;

class PurchaseReport extends Page
{
    protected string $view = 'filament.pages.purchase-report';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Purchase Report';

    protected static ?string $title = 'Purchase Report';

    protected static ?int $navigationSort = 3;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';
}
