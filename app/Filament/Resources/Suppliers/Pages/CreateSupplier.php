<?php

namespace App\Filament\Resources\Suppliers\Pages;

use App\Filament\Resources\Suppliers\SupplierResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Supplier;


class CreateSupplier extends CreateRecord
{
    protected static string $resource = SupplierResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $lastSupplier = Supplier::latest('id')->first();

        $nextNumber = $lastSupplier
            ? ((int) substr($lastSupplier->supplier_code, 3)) + 1
            : 1;

        $data['supplier_code'] = 'SUP' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return $data;
    }
}
