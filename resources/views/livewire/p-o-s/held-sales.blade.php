<div class="rounded-2xl bg-white p-6 shadow">

    <h2 class="mb-4 text-xl font-bold">
        Held Sales
    </h2>

    @forelse ($heldSales as $sale)

        <div class="mb-4 rounded-xl border p-4">

            <div class="flex items-center justify-between">

                <div>

                    <p class="font-semibold">
                        {{ $sale->reference }}
                    </p>

                    <p class="text-sm text-gray-500">
                        {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                    </p>

                    <p class="text-sm">
                        {{ count($sale->cart) }} item(s)
                    </p>

                </div>

                <div class="text-right">

                    <p class="mb-2 font-bold text-green-600">
                        KES {{ number_format($sale->total, 2) }}
                    </p>

                    <button
                        wire:click="resume({{ $sale->id }})"
                        class="rounded-lg bg-[#0F172A] px-4 py-2 text-white hover:bg-slate-800">

                        Resume

                    </button>

                </div>

            </div>

        </div>

    @empty

        <div class="rounded-xl border border-dashed p-6 text-center text-gray-500">

            No held sales.

        </div>

    @endforelse

</div>