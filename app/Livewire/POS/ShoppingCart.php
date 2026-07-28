<?php

namespace App\Livewire\POS;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class ShoppingCart extends Component
{
    public bool $showCheckout = false;

    #[On('product-selected')]
    public function addProduct(array $product): void
    {
        app(CartService::class)->add($product);
    }

    public function removeItem(string $sku): void
    {
        app(CartService::class)->remove($sku);
    }

    public function increaseQuantity(string $sku): void
    {
        app(CartService::class)->increase($sku);
    }

    public function decreaseQuantity(string $sku): void
    {
        app(CartService::class)->decrease($sku);
    }

    public function clearCart(): void
    {
        app(CartService::class)->clear();
    }

    public function getCartProperty(): array
    {
        return app(CartService::class)->all();
    }

    public function getSubtotalProperty(): float
    {
        return app(CartService::class)->subtotal();
    }

    public function getDiscountProperty(): float
    {
        return app(CartService::class)->discount();
    }

    public function getTaxProperty(): float
    {
        return app(CartService::class)->tax();
    }

    public function getTotalProperty(): float
    {
        return app(CartService::class)->total();
    }

    public function getTotalItemsProperty(): int
    {
        return app(CartService::class)->totalItems();
    }

    public function checkout(): void
    {
        $this->showCheckout = true;
    }

    public function closeCheckout(): void
    {
        $this->showCheckout = false;
    }

    #[On('sale-completed')]
    #[On('cartUpdated')]
    public function refreshCart(): void
    {
        $this->showCheckout = false;
    }

    public function render()
    {
        return view('livewire.p-o-s.shopping-cart');
    }
}