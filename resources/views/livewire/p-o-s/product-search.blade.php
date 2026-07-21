<div class="relative w-full">

    <div class="flex items-center bg-white rounded-2xl shadow-sm border border-gray-200 px-4 py-3">

        <svg class="w-6 h-6 text-gray-400 mr-3"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z"/>
        </svg>


        <input
            type="text"
            wire:model.live="search"
            placeholder="Search product or scan barcode..."
            class="w-full bg-transparent border-none outline-none text-lg text-gray-800 placeholder-gray-400"
            autofocus
        >

    </div>


    @if($this->products->count())

        @foreach($this->products as $product)

            <button
                wire:click="selectProduct({{ $product->id }})"
                class="w-full flex justify-between items-center px-5 py-3 hover:bg-gray-100 transition">

                <div>
                    <p class="font-semibold text-gray-800">
                        {{ $product->name }}
                    </p>

                    <p class="text-sm text-gray-500">
                        SKU: {{ $product->sku }}
                    </p>
                </div>


                <span class="font-bold text-slate-900">
                    KES {{ number_format($product->selling_price,2) }}
                </span>

            </button>

        @endforeach

    @else

        <div class="px-5 py-4 text-gray-500 text-center">
            
        </div>

    @endif

</div>