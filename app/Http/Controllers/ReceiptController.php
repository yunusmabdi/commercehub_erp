<?php

namespace App\Http\Controllers;

use App\Models\Sale;

class ReceiptController extends Controller
{
    public function show(Sale $sale)
    {
        $sale->load([
            'customer',
            'items.product',
        ]);

        return view('receipts.invoice', [
            'sale' => $sale,
        ]);
    }
}