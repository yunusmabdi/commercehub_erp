<?php

namespace App\Services;

class CartService
{
    protected string $sessionKey = 'pos_cart';

    public function all(): array
    {
        return session()->get($this->sessionKey, []);
    }

    public function add(array $product): void
    {
        $cart = $this->all();

        $sku = $product['sku'];

        if (isset($cart[$sku])) {

            $cart[$sku]['quantity']++;

        } else {

            $cart[$sku] = [
                'id' => $product['id'],
                'sku' => $product['sku'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
            ];
        }

        session()->put($this->sessionKey, $cart);
    }

    public function increase(string $sku): void
    {
        $cart = $this->all();

        if (isset($cart[$sku])) {
            $cart[$sku]['quantity']++;
        }

        session()->put($this->sessionKey, $cart);
    }

    public function decrease(string $sku): void
    {
        $cart = $this->all();

        if (! isset($cart[$sku])) {
            return;
        }

        if ($cart[$sku]['quantity'] > 1) {

            $cart[$sku]['quantity']--;

        } else {

            unset($cart[$sku]);

        }

        session()->put($this->sessionKey, $cart);
    }

    public function remove(string $sku): void
    {
        $cart = $this->all();

        unset($cart[$sku]);

        session()->put($this->sessionKey, $cart);
    }

    public function clear(): void
    {
        session()->forget($this->sessionKey);
    }

    public function subtotal(): float
    {
        return collect($this->all())
            ->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    public function tax(): float
    {
        return 0;
    }

    public function total(): float
    {
        return $this->subtotal() + $this->tax();
    }

    public function totalItems(): int
    {
        return collect($this->all())
            ->sum('quantity');
    }
}