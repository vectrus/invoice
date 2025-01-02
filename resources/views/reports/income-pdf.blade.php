<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            background-color: white;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        th {
            background-color: #f8fafc;
            font-weight: 600;
        }

        .nested-table {
            margin: 0.5rem 0;
            background-color: #f8fafc;
        }

        .nested-table th, .nested-table td {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .text-right {
            text-align: right;
        }

        /* Fixed widths for main table */
        .main-table th:nth-child(1), .main-table td:nth-child(1) {
            width: 15%;
        }

        .main-table th:nth-child(2), .main-table td:nth-child(2) {
            width: 15%;
        }

        .main-table th:nth-child(3), .main-table td:nth-child(3) {
            width: 25%;
        }

        .main-table th:nth-child(4), .main-table td:nth-child(4) {
            width: 15%;
        }

        .main-table th:nth-child(5), .main-table td:nth-child(5) {
            width: 15%;
        }

        .main-table th:nth-child(6), .main-table td:nth-child(6) {
            width: 15%;
        }

        /* Fixed widths for item tables */
        .items-table th:nth-child(1), .items-table td:nth-child(1) {
            width: 40%;
        }

        .items-table th:nth-child(2), .items-table td:nth-child(2) {
            width: 15%;
        }

        .items-table th:nth-child(3), .items-table td:nth-child(3) {
            width: 15%;
        }

        .items-table th:nth-child(4), .items-table td:nth-child(4) {
            width: 15%;
        }

        .items-table th:nth-child(5), .items-table td:nth-child(5) {
            width: 15%;
        }
    </style>
</head>
<body class="p-4">
<div class="report-container">
    <img src="{{ env('APP_URL') }}/{{ config('settings.logo') }}">
    <h1 class="text-3xl font-bold mb-2">Periodiek inkomsten rapport</h1>
    <h2 class="text-xl text-gray-600 mb-6">{{ $start_date }} - {{ $start_date }}</h2>

    <table class="mb-8 main-table">
        <thead>
        <tr>
            <th>Fact#</th>
            <th>Datum</th>
            <th>Client</th>
            <th class="text-right">Excl.</th>
            <th class="text-right">Incl.</th>
            <th class="text-right">BTW</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr class="hover:bg-gray-50">
                <td class="font-medium">{{ $invoice->invoice_number }}</td>
                <td>{!! \Carbon\Carbon::parse($invoice->issue_date)->format('d-m-Y') !!}</td>
                <td>{{ $invoice->client->companyname }}</td>
                <td class="text-right">€ {{ number_format($invoice->amount_excl, 2) }}</td>
                <td class="text-right">€ {{ number_format($invoice->amount_incl, 2) }}</td>
                <td class="text-right">€ {{ number_format($invoice->amount_incl - $invoice->amount_excl, 2) }}</td>
            </tr>
            <tr>
                <td colspan="6" class="p-0">
                    <table class="nested-table items-table">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th class="text-right">Incl.</th>
                            <th class="text-right">Excl.</th>
                            <th class="text-right">BTW %</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoice->items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-right">€ {{ number_format($item->price, 2) }}</td>
                                <td class="text-right">
                                    € {{ number_format($item->price - ($item->price / 100) * $item->tax_percentage, 2) }}</td>
                                <td class="text-right">{{ number_format($item->tax_percentage, 2) }}%</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="6" class="border-b-2"></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="space-y-2 mt-8 text-right">
        <h2 class="text-xl font-semibold">Totaal Incl.: € {{ number_format($total_income, 2) }}</h2>
        <h2 class="text-xl font-semibold">Totaal Btw.: € {{ number_format($total_income - $total_subtotal, 2) }}</h2>
        <h2 class="text-xl font-semibold">Totaal Excl.: € {{ number_format($total_subtotal, 2) }}</h2>
    </div>
</div>
</body>
</html>
