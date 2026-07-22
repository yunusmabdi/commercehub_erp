<?php

namespace App\Livewire\POS;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class ShoppingCart extends Component
{
    protected CartService $cartService;

    public function boot(CartService $cartService): void
    {
        $this->cartService = $cartService;
    }

    #[On('product-selected')]
    public function addProduct(array $product): void
    {
        $this->cartService->add($product);
    }

    public function removeItem(string $sku): void
    {
        $this->cartService->remove($sku);
    }

    public function increaseQuantity(string $sku): void
    {
        $this->cartService->increase($sku);
    }

    public function decreaseQuantity(string $sku): void
    {
        $this->cartService->decrease($sku);
    }

    public function clearCart(): void
    {
        $this->cartService->clear();
    }

    public function getCartProperty(): array
    {
        return $this->cartService->all();
    }

    public function getSubtotalProperty(): float
    {
        return $this->cartService->subtotal();
    }

    public function getTaxProperty(): float
    {
        return $this->cartService->tax();
    }

    public function getTotalProperty(): float
    {
        return $this->cartService->total();
    }

    public function getTotalItemsProperty(): int
    {
        return $this->cartService->totalItems();
    }

    public function render()
    {
        return view('livewire.p-o-s.shopping-cart');
    }
}