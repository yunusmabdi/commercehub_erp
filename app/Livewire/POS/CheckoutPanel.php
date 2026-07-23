<?php

namespace App\Livewire\POS;

use App\Models\Customer;
use App\Services\CartService;
use App\Services\CheckoutService;
use Livewire\Component;
use RuntimeException;

class CheckoutPanel extends Component
{
    public ?int $customerId = null;

    public string $paymentMethod = 'Cash';

    public float $amountPaid = 0;

    public string $error = '';



    public function getSubtotalProperty(): float
    {
        return app(CartService::class)->subtotal();
    }


    public function getTaxProperty(): float
    {
        return app(CartService::class)->tax();
    }


    public function getTotalProperty(): float
    {
        return app(CartService::class)->total();
    }


    public function getChangeProperty(): float
    {
        return max(
            0,
            $this->amountPaid - $this->total
        );
    }



    public function completeSale()
    {
        $this->error = '';

        $this->validate([
            'paymentMethod' => [
                'required',
                'string',
            ],

            'amountPaid' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        try {

            $sale = app(CheckoutService::class)->checkout(
                $this->customerId,
                $this->paymentMethod,
                $this->amountPaid
            );

            $this->reset([
                'customerId',
                'amountPaid',
            ]);

            $this->paymentMethod = 'Cash';

            return redirect()->route('pos.receipt', [
                'sale' => $sale,
            ]);

        } catch (RuntimeException $e) {

            $this->error = $e->getMessage();

        }
    }




    public function render()
    {

        return view(
            'livewire.p-o-s.checkout-panel',
            [
                'customers' => Customer::orderBy('name')->get()
            ]
        );

    }
}