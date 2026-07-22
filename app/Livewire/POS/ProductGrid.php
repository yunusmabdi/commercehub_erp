<?php

namespace App\Livewire\POS;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;

class ProductGrid extends Component
{   
    public ?int $selectedCategory = null;

    public string $search = '';

    protected $listeners = [
        'search-updated' => 'updateSearch',
    ];

    public function updateSearch($search)
    {
        $this->search = $search;
    }

    public function addProduct($productId)
    {
        $product = Product::findOrFail($productId);

        $this->dispatch(
            'product-selected',
            product: [
                'id'    => $product->id,
                'name'  => $product->name,
                'price' => $product->selling_price,
                'sku'   => $product->sku,
                'cost_price' => $product->cost_price,
            ]
        );
    }

    public function getProductsProperty()
    {
        return Product::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('sku', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.p-o-s.product-grid');
    }

    public function getCategoriesProperty()
    {
        return Category::orderBy('name')->get();
    }
}