<?php

namespace App\Filament\Resources\Sales\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;


class SalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sale_date', 'desc')

            ->columns([

                TextColumn::make('invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'Draft',
                        'success' => 'Completed',
                        'danger' => 'Cancelled',
                    ])
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Grand Total')
                    ->money('KES')
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('sale_date')
                    ->label('Sale Date')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([

                SelectFilter::make('status')
                    ->options([
                        'Draft' => 'Draft',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ]),

                Filter::make('today')
                    ->label('Today')
                    ->query(fn ($query) => $query->whereDate('sale_date', today())),

            ])

            ->recordActions([
                ViewAction::make(),

                Action::make('receipt')
                    ->label('Receipt')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn ($record) => route('receipt.show', ['sale' => $record]))
                    ->openUrlInNewTab(),

                EditAction::make()
                    ->visible(fn ($record) => $record->status === 'Draft'),

                DeleteAction::make()
                    ->visible(fn ($record) => $record->status === 'Draft'),

            ])

            ->toolbarActions([
                
            ]);
    }
}