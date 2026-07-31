<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Customer Report</title>

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

    </style>

</head>

<body>

<h2>
    Customer Report
</h2>

<table>

    <thead>

    <tr>

        <th>Code</th>

        <th>Name</th>

        <th>Phone</th>

        <th>Email</th>

        <th>Address</th>

        <th>Registered</th>

    </tr>

    </thead>

    <tbody>

    @foreach ($customers as $customer)

        <tr>

            <td>
                {{ $customer->customer_code }}
            </td>

            <td>
                {{ $customer->name }}
            </td>

            <td>
                {{ $customer->phone }}
            </td>

            <td>
                {{ $customer->email ?? '-' }}
            </td>

            <td>
                {{ $customer->address ?? '-' }}
            </td>

            <td>
                {{ optional($customer->created_at)->format('d M Y') }}
            </td>

        </tr>

    @endforeach

    </tbody>

</table>

</body>
</html>