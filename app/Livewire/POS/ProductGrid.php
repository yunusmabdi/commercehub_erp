<?php

namespace App\Livewire\POS;

use App\Models\Product;
use Livewire\Component;

class ProductGrid extends Component
{
    public function addToCart(int $sku): void
    {
        $this->dispatch('product-added', sku: $sku);
    }

    public function render()
    {
        return view('livewire.p-o-s.product-grid', [
            'products' => Product::orderBy('name')->get(),
        ]);
    }
}