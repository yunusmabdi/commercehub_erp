<?php

namespace App\Livewire\POS;

use App\Models\Product;
use Livewire\Component;

class ProductSearch extends Component
{
    public string $search = '';

    public function getProductsProperty()
    {
        if (strlen($this->search) < 1) {
            return collect();
        }

        return Product::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->limit(8)
            ->get();
    }


    public function selectProduct($productId)
    {
        $product = Product::findOrFail($productId);


        $this->dispatch(
            'product-selected',
            product: [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->selling_price,
                'sku' => $product->sku,
            ]
        );


        $this->search = '';
    }


    public function render()
    {
        return view('livewire.p-o-s.product-search');
    }

    public function updateSearch()
    {
        $this->dispatch('search-updated', search: $this->search);
    }
}