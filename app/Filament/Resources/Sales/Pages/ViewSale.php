<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SaleResource;
use App\Services\SalesService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

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

                    $salesService = app(SalesService::class);

                    try {

                        $salesService->completeSale($this->record);

                        Notification::make()
                            ->title('Sale processed successfully.')
                            ->success()
                            ->send();

                        $this->refreshFormData([
                            'status',
                        ]);

                    } catch (\RuntimeException $e) {

                        Notification::make()
                            ->title('Unable to Complete Sale')
                            ->body($e->getMessage())
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),

        ];
    }
}