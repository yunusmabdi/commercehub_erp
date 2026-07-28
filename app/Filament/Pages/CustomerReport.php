<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use UnitEnum;

class CustomerReport extends Page
{
    protected string $view = 'filament.pages.customer-report';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Customer Report';

    protected static ?string $title = 'Customer Report';

    protected static ?int $navigationSort = 4;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
}
