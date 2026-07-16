<?php

namespace App\Filament\Resources\Purchases\Tables;

use App\Models\StockMovement;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;


class PurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('purchase_number')
                    ->label('Purchase No.')
                    ->searchable()
                    ->sortable(),


                TextColumn::make('supplier.company_name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),


                TextColumn::make('purchase_date')
                    ->date()
                    ->sortable(),


                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray' => 'Draft',
                        'warning' => 'Ordered',
                        'success' => 'Received',
                        'danger' => 'Cancelled',
                    ]),


                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('KES')
                    ->sortable(),


                TextColumn::make('created_at')
                    ->since(),

            ])

            ->filters([
                //
            ])

            ->recordActions([

                Action::make('receive')

                    ->label('Receive')

                    ->icon('heroicon-o-check-circle')

                    ->color('success')

                    ->requiresConfirmation()

                    ->visible(fn ($record) => $record->status === 'Ordered')


                    ->action(function ($record) {


                        foreach ($record->items as $item) {


                            // Increase product stock

                            $item->product->increment(

                                'stock_quantity',

                                $item->quantity

                            );


                            // Create stock movement record

                            StockMovement::create([

                                'product_id' => $item->product_id,

                                'type' => 'IN',

                                'quantity' => $item->quantity,

                                'reference_type' => 'Purchase',

                                'reference_id' => $record->id,

                                'user_id' => auth()->id(),

                                'description' => 
                                    'Stock received from purchase ' 
                                    . $record->purchase_number,

                            ]);

                        }



                        // Update purchase status

                        $record->update([

                            'status' => 'Received',

                        ]);



                        Notification::make()

                            ->title('Purchase received successfully.')

                            ->success()

                            ->send();


                    }),



                EditAction::make(),

            ])



            ->toolbarActions([

                BulkActionGroup::make([

                    DeleteBulkAction::make(),

                ]),

            ]);

    }
}