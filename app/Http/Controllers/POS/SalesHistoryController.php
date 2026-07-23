<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Sale;

class SalesHistoryController extends Controller
{
    public function index()
    {
        $sales = Sale::with([
                'customer',
                'items',
            ])
            ->latest('sale_date')
            ->paginate(20);

        return view('pos.history', compact('sales'));
    }
}