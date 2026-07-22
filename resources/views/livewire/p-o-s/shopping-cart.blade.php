<div class="rounded-xl bg-white dark:bg-gray-900 shadow p-6 sticky top-6">

    <h2 class="mb-6 text-xl font-bold">
        🛒 Shopping Cart
    </h2>

    @forelse($this->cart as $item)

        <div class="border-b py-4">

            <div class="flex items-start justify-between">

                <div>
                    <h3 class="font-semibold text-[#0F172A]">
                        {{ $item['name'] }}
                    </h3>

                    <p class="text-sm text-gray-500">
                        {{ $item['sku'] }}
                    </p>

                    <p class="mt-1 font-semibold text-primary-600">
                        KES {{ number_format($item['price'], 2) }}
                    </p>
                </div>

                <button
                    wire:click="removeItem('{{ $item['sku'] }}')"
                    class="text-red-500 hover:text-red-700"
                >
                    ✕
                </button>

            </div>

            <div class="mt-3 flex items-center justify-between">

                <div class="flex items-center gap-2">

                    <button
                        wire:click="decreaseQuantity('{{ $item['sku'] }}')"
                        class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 transition">
                        −
                    </button>

                    <span class="w-8 text-center font-bold">
                        {{ $item['quantity'] }}
                    </span>

                    <button
                        wire:click="increaseQuantity('{{ $item['sku'] }}')"
                        class="w-8 h-8 rounded-full bg-[#0F172A] text-white hover:bg-slate-800 transition">
                        +
                    </button>

                </div>

                <div class="font-bold">
                    KES {{ number_format($item['price'] * $item['quantity'], 2) }}
                </div>

            </div>

        </div>

    @empty

        <div class="py-10 text-center text-gray-500">

            <div class="text-5xl mb-3">
                🛒
            </div>

            <p>No products added.</p>

        </div>

    @endforelse

    <div class="mt-6 border-t pt-4">

        <div class="flex justify-between">
            <span>Subtotal</span>
            <span>KES {{ number_format($this->subtotal, 2) }}</span>
        </div>

        <div class="mt-2 flex justify-between">
            <span>Tax</span>
            <span>KES {{ number_format($this->tax, 2) }}</span>
        </div>

        <div class="mt-4 flex justify-between text-xl font-bold text-[#0F172A]">
            <span>Total</span>
            <span>KES {{ number_format($this->total, 2) }}</span>
        </div>

        <button
            wire:click="clearCart"
            class="w-full mb-3 rounded-xl border border-red-200 py-3 text-red-600 hover:bg-red-50 transition">
            Clear Cart
        </button>

        <button
            class="mt-6 w-full rounded-xl bg-[#0F172A] py-3 text-white font-semibold hover:bg-slate-800 transition">
            Complete Sale
        </button>

    </div>

</div>