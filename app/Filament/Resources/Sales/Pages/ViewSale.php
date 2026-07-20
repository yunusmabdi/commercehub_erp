<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SaleResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ViewSale extends ViewRecord
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\Action::make('completeSale')
                ->label('Complete Sale')
                ->icon('heroicon-o-check-circle')
                ->color('success')

                ->visible(fn () => $this->record->status === 'Draft')

                ->requiresConfirmation()

                ->modalHeading('Complete Sale')

                ->modalDescription(
                    'This will finalize the sale and deduct inventory.'
                )

                ->action(function () {
                    if ($this->record->status !== 'Draft') {

                        Notification::make()
                            ->title('Sale Already Completed')
                            ->body('This sale has already been processed.')
                            ->warning()
                            ->send();

                        return;
                    }
                    DB::transaction(function () {

                        // Validate stock
                        foreach ($this->record->items as $item) {

                            $product = $item->product;

                            if (! $product) {
                                continue;
                            }

                            if ($product->stock_quantity < $item->quantity) {

                                Notification::make()
                                    ->title('Insufficient Stock')
                                    ->body(
                                        "{$product->name} has only {$product->stock_quantity} item(s) available, but {$item->quantity} were requested."
                                    )
                                    ->danger()
                                    ->persistent()
                                    ->send();

                                return;
                            }
                        }

                        // Deduct stock + create movement
                        foreach ($this->record->items as $item) {

                            $product = $item->product;

                            if (! $product) {
                                continue;
                            }

                            $product->decrement('stock_quantity', $item->quantity);

                            StockMovement::create([
                                'product_id' => $product->id,
                                'type' => 'OUT',
                                'quantity' => $item->quantity,
                                'reference_type' => 'Sale',
                                'reference_id' => $this->record->id,
                                'user_id' => Auth::id(),
                                'description' => "Sale {$this->record->invoice_number}",
                            ]);
                        }
                        $this->record->update([
                            'status' => 'Completed',
                        ]);

                        Notification::make()
                            ->title('Sale processed successfully.')
                            ->success()
                            ->send();

                    });

                })

        ];
    }
}