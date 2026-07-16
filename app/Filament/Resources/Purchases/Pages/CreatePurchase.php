<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\Purchase;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $lastPurchase = Purchase::latest('id')->first();

        $nextNumber = $lastPurchase
            ? ((int) substr($lastPurchase->purchase_number, 2)) + 1
            : 1;

        $data['purchase_number'] = 'PO' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return $data;
    }
    protected function afterCreate(): void
    {
        $this->record->calculateTotal();
    }
}