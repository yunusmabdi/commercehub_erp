<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sales History</title>

    @vite(['resources/css/app.css'])

</head>

<body class="bg-slate-100 min-h-screen">

<div class="max-w-7xl mx-auto py-6 px-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-2xl font-bold text-[#0F172A]">
                Sales History
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                View completed sales and reprint receipts.
            </p>

        </div>

        <a
            href="{{ route('pos') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#0F172A] text-white text-sm font-semibold hover:bg-slate-800 transition">

            ← POS

        </a>

    </div>


    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-lg border border-slate-200">

        <table class="w-full">

            <thead class="bg-slate-50 border-b border-slate-200">

            <tr>

                <th class="px-4 py-3 text-left text-xs uppercase tracking-wide font-semibold text-slate-600">
                    Invoice
                </th>

                <th class="px-4 py-3 text-left text-xs uppercase tracking-wide font-semibold text-slate-600">
                    Customer
                </th>

                <th class="px-4 py-3 text-left text-xs uppercase tracking-wide font-semibold text-slate-600">
                    Date
                </th>

                <th class="px-4 py-3 text-right text-xs uppercase tracking-wide font-semibold text-slate-600">
                    Total
                </th>

                <th class="px-4 py-3 text-center text-xs uppercase tracking-wide font-semibold text-slate-600">
                    Status
                </th>

                <th class="px-4 py-3 text-center text-xs uppercase tracking-wide font-semibold text-slate-600">
                    Actions
                </th>

            </tr>

            </thead>

            <tbody>

            @forelse($sales as $sale)

                <tr class="border-b border-slate-100 odd:bg-white even:bg-slate-50 hover:bg-blue-50 transition">

                    <td class="px-4 py-3 font-semibold text-[#0F172A] text-sm">
                        {{ $sale->invoice_number }}
                    </td>

                    <td class="px-4 py-3 text-sm">

                        {{ $sale->customer?->name ?? 'Walk-in Customer' }}

                    </td>

                    <td class="px-4 py-3 text-sm text-slate-500">

                        {{ $sale->sale_date->format('d M Y') }}

                        <div class="text-xs text-slate-400">

                            {{ $sale->sale_date->format('H:i') }}

                        </div>

                    </td>

                    <td class="px-4 py-3 text-right font-semibold text-green-600 text-sm">

                        KES {{ number_format($sale->total_amount, 2) }}

                    </td>

                    <td class="px-4 py-3 text-center">

                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">

                            {{ $sale->status }}

                        </span>

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-center gap-2">

                            <a
                                href="{{ route('pos.receipt', $sale) }}"
                                class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700 transition">

                                👁

                            </a>

                            <a
                                href="{{ route('pos.receipt', $sale) }}"
                                target="_blank"
                                class="rounded-lg bg-slate-700 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800 transition">

                                🖨

                            </a>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="py-12 text-center text-slate-500">

                        No sales found.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
    <div class="mt-5">

        {{ $sales->links() }}

    </div>

</div>

</body>

</html>