<?php

namespace App\Services;

use App\Models\HeldSale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HeldSaleService
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Save the current cart as a held sale.
     */
    public function hold(?int $customerId = null): HeldSale
    {
        $cart = $this->cartService->all();

        if (empty($cart)) {
            throw new \RuntimeException('Cannot hold an empty cart.');
        }

        $heldSale = HeldSale::create([
            'reference'   => $this->generateReference(),
            'customer_id' => $customerId,
            'user_id'     => Auth::id(),
            'cart'        => $cart,
            'subtotal'    => $this->cartService->subtotal(),
            'tax'         => $this->cartService->tax(),
            'total'       => $this->cartService->total(),
        ]);

        $this->cartService->clear();

        return $heldSale;
    }

    /**
     * Restore a held sale into the cart.
     */
    public function resume(HeldSale $heldSale): void
    {
        $this->cartService->replace($heldSale->cart);

        $heldSale->delete();
    }

    /**
     * Return all held sales.
     */
    public function all()
    {
        return HeldSale::where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    /**
     * Delete a held sale.
     */
    public function delete(HeldSale $heldSale): void
    {
        $heldSale->delete();
    }

    /**
     * Generate a unique reference.
     */
    protected function generateReference(): string
    {
        do {
            $reference = 'HS-' . strtoupper(Str::random(6));
        } while (HeldSale::where('reference', $reference)->exists());

        return $reference;
    }
}