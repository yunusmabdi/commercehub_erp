<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStockProducts extends TableWidget
{
    protected static ?string $heading = 'Low Stock Products';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn () => Product::query()
                    ->whereColumn(
                        'stock_quantity',
                        '<=',
                        'minimum_stock'
                    )
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Product'),

                TextColumn::make('stock_quantity')
                    ->label('Stock'),

                TextColumn::make('minimum_stock')
                    ->label('Minimum Stock'),
            ]);
    }
}