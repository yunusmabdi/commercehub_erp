<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Sales Report</title>

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

        th, td {
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
    Sales Report
</h2>


<table>

<thead>

<tr>

<th>Invoice</th>

<th>Customer</th>

<th>Status</th>

<th>Date</th>

<th>Subtotal</th>

<th>Tax</th>

<th>Discount</th>

<th>Total</th>

</tr>

</thead>


<tbody>

@foreach($sales as $sale)

<tr>

<td>
{{ $sale->invoice_number }}
</td>


<td>
{{ $sale->customer?->name ?? 'Walk-in Customer' }}
</td>


<td>
{{ $sale->status }}
</td>


<td>
{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}
</td>


<td class="money">
KES {{ number_format($sale->subtotal, 2) }}
</td>


<td class="money">
KES {{ number_format($sale->tax, 2) }}
</td>


<td class="money">
KES {{ number_format($sale->discount, 2) }}
</td>


<td class="money total">
KES {{ number_format($sale->total_amount, 2) }}
</td>


</tr>

@endforeach


</tbody>

</table>


</body>

</html>