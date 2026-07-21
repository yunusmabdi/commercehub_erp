<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PointOfSale extends Page
{
    protected string $view = 'filament.pages.point-of-sale';

    protected static ?string $title = 'Point Of Sale';

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}