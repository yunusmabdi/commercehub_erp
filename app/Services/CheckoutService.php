<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CheckoutService
{
    public function __construct(
        protected CartService $cartService,
        protected SalesService $salesService,
    ) {
    }

    /**
     * Complete a POS checkout.
     */
    public function checkout(
        ?int $customerId,
        string $paymentMethod,
        float $amountPaid
    ): Sale {

        return DB::transaction(function () use (
            $customerId,
            $paymentMethod,
            $amountPaid
        ) {

            $cart = $this->cartService->all();

            if (empty($cart)) {
                throw new RuntimeException('The shopping cart is empty.');
            }

            $subtotal = $this->cartService->subtotal();
            $discount = $this->cartService->discount();
            $tax = $this->cartService->tax();
            $total = $this->cartService->total();

            if ($amountPaid < $total) {
                throw new RuntimeException('Amount paid is less than the sale total.');
            }

            $change = $amountPaid - $total;

            /*
             |--------------------------------------------------------------------------
             | Create Sale
             |--------------------------------------------------------------------------
             */

            $sale = Sale::create([
                'customer_id'    => $customerId,
                'sale_date'      => now(),
                'status'         => 'Draft',

                'payment_method' => $paymentMethod,

                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'tax'            => $tax,
                'total_amount'   => $total,

                'amount_paid'    => $amountPaid,
                'change_amount'  => $change,

                'notes'          => null,
            ]);

            /*
             |--------------------------------------------------------------------------
             | Create Sale Items
             |--------------------------------------------------------------------------
             */

            foreach ($cart as $item) {

                $product = Product::findOrFail($item['id']);

                $sale->items()->create([
                    'product_id'      => $product->id,

                    'quantity'        => $item['quantity'],

                    'original_price'  => $item['original_price'],

                    // Price after discount
                    'unit_price'      => $item['discounted_price'],

                    // Discount per unit
                    'discount_amount' => $item['discount'],

                    'cost_price'      => $product->cost_price,

                    // Final amount charged
                    'line_total'      => $item['discounted_price'] * $item['quantity'],
                ]);
            }

            /*
             |--------------------------------------------------------------------------
             | Complete Sale
             |--------------------------------------------------------------------------
             */

            $this->salesService->completeSale($sale);

            /*
             |--------------------------------------------------------------------------
             | Clear Cart
             |--------------------------------------------------------------------------
             */

            $this->cartService->clear();

            return $sale->fresh([
                'customer',
                'items.product',
            ]);
        });
    }
}