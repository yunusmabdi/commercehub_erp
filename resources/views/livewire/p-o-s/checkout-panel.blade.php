<div class="space-y-6 text-gray-900">

    {{-- Error Message --}}
    @if($error)

        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-600">
            {{ $error }}
        </div>

    @endif


    {{-- Customer --}}
    <div>

        <label class="mb-2 block font-semibold">
            Customer
        </label>

        <select
            wire:model.live="customerId"
            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900">

            <option value="">
                Walk-in Customer
            </option>

            @foreach($customers as $customer)

                <option value="{{ $customer->id }}">
                    {{ $customer->name }}
                </option>

            @endforeach

        </select>

    </div>



    {{-- Payment Method --}}
    <div>

        <label class="mb-2 block font-semibold">
            Payment Method
        </label>

        <select
            wire:model.live="paymentMethod"
            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900">

            <option value="Cash">
                Cash
            </option>

            <option value="M-Pesa">
                M-Pesa
            </option>

            <option value="Card">
                Card
            </option>

        </select>

    </div>



    {{-- Amount Paid --}}
    <div>

        <label class="mb-2 block font-semibold">
            Amount Paid
        </label>

        <input
            type="number"
            step="0.01"
            wire:model.live="amountPaid"
            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900"
            placeholder="Enter amount paid">

    </div>



    {{-- Summary --}}
    <div class="rounded-xl bg-gray-50 p-5">

        <div class="space-y-3">


            <div class="flex justify-between">

                <span>
                    Subtotal
                </span>

                <span class="font-semibold text-green-600">
                    KES {{ number_format($this->subtotal, 2) }}
                </span>

            </div>



            <div class="flex justify-between">

                <span>
                    Tax
                </span>

                <span class="font-semibold text-green-600">
                    KES {{ number_format($this->tax, 2) }}
                </span>

            </div>



            <div class="flex justify-between border-t pt-3 text-xl font-bold">

                <span>
                    Total
                </span>

                <span class="text-green-600">
                    KES {{ number_format($this->total, 2) }}
                </span>

            </div>



            <div class="flex justify-between">

                <span>
                    Change
                </span>

                <span class="font-semibold text-green-600">
                    KES {{ number_format($this->change, 2) }}
                </span>

            </div>


        </div>

    </div>



    {{-- Complete Sale --}}
    <button
        wire:click="completeSale"
        wire:loading.attr="disabled"
        class="w-full rounded-xl bg-[#0F172A] py-4 text-lg font-bold text-white transition hover:bg-slate-800">

        <span wire:loading.remove>
            Complete Sale
        </span>

        <span wire:loading>
            Processing...
        </span>

    </button>


</div>