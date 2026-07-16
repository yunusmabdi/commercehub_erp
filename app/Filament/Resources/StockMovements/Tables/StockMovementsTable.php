<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')

            ->columns([

                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'success' => 'IN',
                        'danger' => 'OUT',
                    ]),

                TextColumn::make('quantity')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('reference_type')
                    ->label('Reference')
                    ->badge(),

                TextColumn::make('reference_id')
                    ->label('Reference ID'),

                TextColumn::make('user.name')
                    ->label('Processed By')
                    ->searchable(),

                TextColumn::make('description')
                    ->limit(40)
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Movement Date')
                    ->dateTime()
                    ->sortable(),

            ])

            ->filters([
                //
            ])

            ->recordActions([
                ViewAction::make(),
            ])

            ->toolbarActions([
                //
            ]);
    }
}
