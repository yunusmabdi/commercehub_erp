<?php

namespace App\Livewire\POS;

use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;

class ShoppingCart extends Component
{
    public array $cart = [];

    #[On('product-added')]
    public function addProduct(string $sku): void
    {
        $product = Product::where('sku', $sku)->firstOrFail();

        if (isset($this->cart[$sku])) {
            $this->cart[$sku]['quantity']++;
        } else {
            $this->cart[$sku] = [
                'sku' => $product->sku,
                'name' => $product->name,
                'price' => $product->selling_price,
                'quantity' => 1,
            ];
        }
    }

    public function removeItem(string $sku): void
    {
        unset($this->cart[$sku]);
    }

    public function increaseQuantity(string $sku): void
    {
        $this->cart[$sku]['quantity']++;
    }

    public function decreaseQuantity(string $sku): void
    {
        if ($this->cart[$sku]['quantity'] > 1) {
            $this->cart[$sku]['quantity']--;
        } else {
            unset($this->cart[$sku]);
        }
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->cart)->sum(
            fn ($item) => $item['price'] * $item['quantity']
        );
    }

    public function getTaxProperty(): float
    {
        return 0;
    }

    public function getTotalProperty(): float
    {
        return $this->subtotal + $this->tax;
    }

    public function render()
    {
        return view('livewire.p-o-s.shopping-cart');
    }
}