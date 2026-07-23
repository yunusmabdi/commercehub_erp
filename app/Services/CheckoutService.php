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
                'customer_id'     => $customerId,
                'sale_date'       => now(),
                'status'          => 'Draft',

                'payment_method'  => $paymentMethod,

                'subtotal'        => $subtotal,
                'discount'        => 0,
                'tax'             => $tax,
                'total_amount'    => $total,

                'amount_paid'     => $amountPaid,
                'change_amount'   => $change,

                'notes'           => null,
            ]);

            /*
             |--------------------------------------------------------------------------
             | Create Sale Items
             |--------------------------------------------------------------------------
             */

            foreach ($cart as $item) {

                $product = Product::findOrFail($item['id']);

                $sale->items()->create([

                    'product_id' => $product->id,

                    'quantity'   => $item['quantity'],

                    'unit_price' => $item['price'],

                    'cost_price' => $product->cost_price,

                    'line_total' => $item['price'] * $item['quantity'],

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