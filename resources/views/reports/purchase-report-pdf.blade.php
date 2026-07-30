<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Purchase Report</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
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

        .total {
            font-weight: bold;
        }

    </style>

</head>

<body>

<h2>
    Purchase Report
</h2>

<table>

    <thead>

    <tr>

        <th>Purchase Number</th>

        <th>Supplier</th>

        <th>Purchase Date</th>

        <th>Status</th>

        <th>Total Amount</th>

    </tr>

    </thead>

    <tbody>

    @foreach ($purchases as $purchase)

        <tr>

            <td>
                {{ $purchase->purchase_number }}
            </td>

            <td>
                {{ $purchase->supplier?->company_name ?? 'N/A' }}
            </td>

            <td>
                {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}
            </td>

            <td>
                {{ $purchase->status }}
            </td>

            <td class="money total">
                KES {{ number_format($purchase->total_amount, 2) }}
            </td>

        </tr>

    @endforeach

    </tbody>

</table>

</body>
</html>