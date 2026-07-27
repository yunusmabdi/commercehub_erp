<?php

namespace App\Livewire\POS;

use App\Models\HeldSale;
use App\Services\HeldSaleService;
use Livewire\Component;

class HeldSales extends Component
{
    public function resume(int $heldSaleId): void
    {
        $heldSale = HeldSale::findOrFail($heldSaleId);

        app(HeldSaleService::class)->resume($heldSale);

        $this->dispatch('cartUpdated');

        session()->flash(
            'success',
            'Held sale restored successfully.'
        );
    }

    public function render()
    {
        return view('livewire.p-o-s.held-sales', [
            'heldSales' => app(HeldSaleService::class)->all(),
        ]);
    }
}