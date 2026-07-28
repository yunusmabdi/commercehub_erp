<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use UnitEnum;

class InventoryReport extends Page
{
    protected string $view = 'filament.pages.inventory-report';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Inventory Report';

    protected static ?string $title = 'Inventory Report';

    protected static ?int $navigationSort = 2;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';
}
