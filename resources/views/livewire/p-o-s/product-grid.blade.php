<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

    @forelse($products as $product)

        <div
            wire:click="addToCart('{{ $product->sku }}')"
            class="cursor-pointer rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-primary-500 hover:shadow-lg dark:border-gray-700"
        >

            <div class="flex justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary-100 text-3xl dark:bg-primary-900">
                    📦
                </div>
            </div>

            <div class="mt-4 text-center">

                <h3 class="truncate text-lg font-bold text-gray-900">
                    {{ $product->name }}
                </h3>

                <p class="mt-2 text-xl font-bold text-primary-600">
                    KES {{ number_format($product->selling_price, 2) }}
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    SKU: {{ $product->sku }}
                </p>

                <p class="text-sm text-gray-500">
                    Stock: {{ $product->stock_quantity }}
                </p>

            </div>

            <button
                type="button"
                class="mt-5 w-full rounded-lg bg-primary-600 px-4 py-2 font-semibold text-white"
            >
                + Add
            </button>

        </div>

    @empty

        <div class="col-span-full rounded-xl border border-dashed p-10 text-center text-gray-500">
            No products found.
        </div>

    @endforelse

</div>