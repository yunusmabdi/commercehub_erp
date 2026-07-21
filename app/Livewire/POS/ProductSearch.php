<?php

namespace App\Livewire\POS;

use Livewire\Component;

class ProductSearch extends Component
{
    public string $search = '';

    public function render()
    {
        return view('livewire.p-o-s.product-search');
    }
}