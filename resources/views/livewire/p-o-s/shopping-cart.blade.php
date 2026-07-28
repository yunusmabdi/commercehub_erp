<div class="rounded-2xl bg-white shadow-xl text-gray-900">

    {{-- Header --}}
    <div class="border-b px-6 py-5">
        <div class="flex items-center justify-between">

            <h2 class="text-xl font-bold text-[#0F172A]">
                🛒 Shopping Cart
            </h2>

            <span class="rounded-full bg-[#0F172A] px-3 py-1 text-sm font-bold text-white">
                {{ $this->totalItems }}
            </span>

        </div>
    </div>

    {{-- Cart Items --}}
    <div class="max-h-[500px] overflow-y-auto px-6">

        @forelse($this->cart as $item)

            <div class="border-b py-5">

                <div class="flex justify-between">

                    <div>

                        <h3 class="font-semibold text-[#0F172A]">
                            {{ $item['name'] }}
                        </h3>

                        <p class="text-sm text-gray-500">
                            {{ $item['sku'] }}
                        </p>

                        @if(($item['discount'] ?? 0) > 0)

                            <div class="mt-2">

                                <span class="text-sm text-gray-400 line-through">
                                    KES {{ number_format($item['original_price'], 2) }}
                                </span>

                                <span class="ml-2 font-bold text-red-600">
                                    KES {{ number_format($item['discounted_price'], 2) }}
                                </span>

                            </div>

                        @else

                            <p class="mt-2 font-semibold text-green-600">
                                KES {{ number_format($item['original_price'], 2) }}
                            </p>

                        @endif

                    </div>

                    <button
                        wire:click="removeItem('{{ $item['sku'] }}')"
                        class="text-lg text-red-500 hover:text-red-700">

                        ✕

                    </button>

                </div>

                <div class="mt-4 flex items-center justify-between">

                    <div class="flex items-center gap-3">

                        <button
                            wire:click="decreaseQuantity('{{ $item['sku'] }}')"
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-200 font-bold">

                            −

                        </button>

                        <span class="w-8 text-center font-bold">
                            {{ $item['quantity'] }}
                        </span>

                        <button
                            wire:click="increaseQuantity('{{ $item['sku'] }}')"
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-[#0F172A] font-bold text-white">

                            +

                        </button>

                    </div>

                    <span class="font-bold text-[#0F172A]">

                        KES
                        {{ number_format(($item['discounted_price'] ?? $item['original_price']) * $item['quantity'], 2) }}

                    </span>

                </div>

            </div>

        @empty

            <div class="py-16 text-center">

                <div class="mb-3 text-6xl">
                    🛒
                </div>

                <p class="font-semibold text-gray-500">
                    Cart is empty
                </p>

            </div>

        @endforelse

    </div>

    {{-- Totals --}}
    <div class="border-t bg-gray-50 px-6 py-5">

        <div class="space-y-3">

            <div class="flex justify-between">

                <span>Subtotal</span>

                <span class="font-semibold">
                    KES {{ number_format($this->subtotal, 2) }}
                </span>

            </div>

            <div class="flex justify-between text-red-600">

                <span>Discount</span>

                <span>
                    -KES {{ number_format($this->discount, 2) }}
                </span>

            </div>

            <div class="flex justify-between">

                <span>Tax</span>

                <span>
                    KES {{ number_format($this->tax, 2) }}
                </span>

            </div>

            <div class="flex justify-between border-t pt-3 text-xl font-bold">

                <span>Total</span>

                <span class="text-green-600">
                    KES {{ number_format($this->total, 2) }}
                </span>

            </div>

        </div>

        <div class="mt-6 space-y-3">

            <button
                wire:click="clearCart"
                class="w-full rounded-xl border border-red-300 py-3 font-semibold text-red-600 hover:bg-red-50">

                Clear Cart

            </button>

            <button
                wire:click="checkout"
                class="w-full rounded-xl bg-[#0F172A] py-4 font-bold text-white hover:bg-slate-800"
                @disabled($this->totalItems == 0)>

                Checkout

            </button>

        </div>

    </div>

    {{-- Checkout Drawer --}}
    @if($showCheckout)

        <div class="fixed inset-0 z-50 bg-black/50">

            <div class="absolute right-0 top-0 flex h-full w-full max-w-md flex-col overflow-y-auto bg-white shadow-2xl">

                <div class="flex items-center justify-between border-b px-6 py-5">

                    <h2 class="text-2xl font-bold text-[#0F172A]">
                        Checkout
                    </h2>

                    <button
                        wire:click="closeCheckout"
                        class="text-3xl text-gray-400 hover:text-red-500">

                        ×

                    </button>

                </div>

                <div class="flex-1 overflow-y-auto p-6">

                    <livewire:p-o-s.checkout-panel />

                </div>

            </div>

        </div>

    @endif

</div>