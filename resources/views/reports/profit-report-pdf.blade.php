<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Profit Report</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .summary {
            margin-bottom: 20px;
        }

        .summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary td {
            border: 1px solid #ddd;
            padding: 8px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f1f5f9;
            text-align: left;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .money {
            text-align: right;
        }

        .profit {
            font-weight: bold;
        }

    </style>

</head>

<body>

<h2>Profit Report</h2>

<div class="summary">

    <table>

        <tr>

            <td>
                Revenue
            </td>

            <td class="money">
                {{ number_format($totals['revenue'], 2) }}
            </td>

            <td>
                Cost
            </td>

            <td class="money">
                {{ number_format($totals['cost'], 2) }}
            </td>

        </tr>

        <tr>

            <td>
                Gross Profit
            </td>

            <td class="money">
                {{ number_format($totals['profit'], 2) }}
            </td>

            <td>
                Margin
            </td>

            <td class="money">
                {{ number_format($totals['margin'], 2) }}%
            </td>

        </tr>

    </table>

</div>

<table>

    <thead>

    <tr>

        <th>Invoice</th>

        <th>Date</th>

        <th>Customer</th>

        <th>Product</th>

        <th>Qty</th>

        <th>Revenue</th>

        <th>Cost</th>

        <th>Profit</th>

    </tr>

    </thead>

    <tbody>

    @foreach ($items as $item)

        <tr>

            <td>{{ $item->sale->invoice_number }}</td>

            <td>{{ \Carbon\Carbon::parse($item->sale->sale_date)->format('d M Y') }}</td>

            <td>{{ $item->sale->customer?->name ?? 'Walk-in Customer' }}</td>

            <td>{{ $item->product?->name }}</td>

            <td>{{ $item->quantity }}</td>

            <td class="money">
                {{ number_format($item->quantity * $item->unit_price, 2) }}
            </td>

            <td class="money">
                {{ number_format($item->quantity * $item->cost_price, 2) }}
            </td>

            <td class="money profit">
                {{ number_format(($item->quantity * $item->unit_price) - ($item->quantity * $item->cost_price), 2) }}
            </td>

        </tr>

    @endforeach

    </tbody>

</table>

</body>
</html>