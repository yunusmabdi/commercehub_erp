<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Inventory Report</title>

    <style>

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th{
            background:#f1f5f9;
            text-align:left;
        }

        th,
        td{
            border:1px solid #ddd;
            padding:8px;
        }

        .money{
            text-align:right;
        }

        .center{
            text-align:center;
        }

    </style>

</head>

<body>

<h2>Inventory Report</h2>

<table>

    <thead>

        <tr>

            <th>Product</th>

            <th>SKU</th>

            <th>Category</th>

            <th>Cost Price</th>

            <th>Selling Price</th>

            <th>Current Stock</th>

            <th>Min Stock</th>

            <th>Status</th>

        </tr>

    </thead>

    <tbody>

    @foreach($products as $product)

        <tr>

            <td>{{ $product->name }}</td>

            <td>{{ $product->sku }}</td>

            <td>{{ $product->category?->name }}</td>

            <td class="money">
                KES {{ number_format($product->cost_price,2) }}
            </td>

            <td class="money">
                KES {{ number_format($product->selling_price,2) }}
            </td>

            <td class="center">
                {{ $product->stock_quantity }}
            </td>

            <td class="center">
                {{ $product->minimum_stock }}
            </td>

            <td>

                @if($product->stock_quantity == 0)

                    Out of Stock

                @elseif($product->stock_quantity <= $product->minimum_stock)

                    Low Stock

                @else

                    In Stock

                @endif

            </td>

        </tr>

    @endforeach

    </tbody>

</table>

</body>
</html>