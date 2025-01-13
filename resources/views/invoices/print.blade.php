<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        {!! $template->content !!}
    </style>
</head>
<body>
<div class="invoice">
    <div class="header">
        <div class="logo-section">
            {{--@if(file_exists(storage_path( $imageUrl)))--}}
            <img class="company-logo"
                 src="data:image/png;base64, {!! base64_encode(file_get_contents('storage/'.$imageUrl)) !!}"
                 {{--{{ storage_path('app/public/'.$imageUrl) }}--}}

                 alt="{{ config('setting.companyname') }}"/>
            {{--@endif--}}

            {{--<img class="company-logo" src="/{{ $imageUrl }}" alt="Vectrus Internet"/>--}}
        </div>
        <h1 class="invoice-title">FAKTUUR</h1>
        <br><br>
        <div class="info-container">
            <div class="client-info">
                <div class="client-details">
                    <p class="client-name" style="margin: 0">
                        <span class="label">Aan:</span><br>
                        <strong>{{ $invoice->client->companyname }}</strong><br>
                        {{ $invoice->client->address }}<br>
                        {{ $invoice->client->postalcode }} {{ $invoice->client->city }}
                    </p>
                    @if($invoice->client->vat_number)
                        <p class="vat-number" style="margin: 2mm 0 0 0">BTW-nummer
                            klant: {{ $invoice->client->vat_number }}</p>
                    @endif
                </div>
            </div>

            <div class="invoice-details">
                <table class="info-table">
                    <tr>
                        <td class="label"><strong>Factuurnummer:</strong></td>
                        <td>{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td class="label"><strong>Datum:</strong></td>
                        <td>{{ $invoice->issue_date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label"><strong>Betalen voor:</strong></td>
                        <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label"><strong>Bank:</strong></td>
                        <td>{{ $settings['iban'] }}</td>
                    </tr>
                    <tr>
                        <td class="label"><strong>Referentie:</strong></td>
                        <td>{{ $invoice->invoice_number }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="items-section">


        <table class="items-table">
            <thead>
            <tr>
                <th class="desc-col">Omschrijving</th>
                <th class="num-col">Hv</th>
                <th class="num-col">Stuksprijs</th>
                <th class="num-col">BTW%</th>
                <th class="num-col">Subtotaal</th>
                <th class="num-col">BTW</th>
                <th class="num-col">Totaal</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="num-col accent">{{ $item->quantity }}</td>
                    <td class="num-col">€{{ number_format($item->price, 2) }}</td>
                    <td class="num-col accent">{{ $item->tax_percentage }}%</td>
                    <td class="num-col">€{{ number_format($item->calculateSubtotal(), 2) }}</td>
                    <td class="num-col accent">€{{ number_format($item->calculateTax(), 2) }}</td>
                    <td class="num-col">€{{ number_format($item->calculateTotal(), 2) }}</td>
                </tr>
                @if($item->notes)
                    <tr class="notes-row">
                        <td colspan="7">{{ $item->notes }}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
            <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right"><strong>Totaal te betalen:</strong></td>
                <td class="num-col">{{--€{{ number_format($invoice->calculateTotalExcl(), 2) }}--}}</td>
                <td class="num-col">{{--€{{ number_format($invoice->calculateTotalTax(), 2) }}--}}</td>
                <td class="num-col">€{{ number_format($invoice->calculateTotal(), 2) }}</td>
            </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer-container">
        <br>
        <div class="footer-info">
            <div class="payment-info">
                <h4>Betaling via:</h4>
                <p>
                    <strong>Bank:</strong><br/> {{ $settings['iban'] }}<br>

                    {{--<strong>Referentie:</strong> {{ $invoice->invoice_number }}--}}
                </p>
            </div>

            <div class="company-info">
                <h4>Bedrijfsgegevens:</h4>
                <p>
                    <strong>{{ $settings['companyname'] }}</strong><br>
                    {{ $settings['companyaddress'] }}<br>
                    {{ $settings['postcode'] }} {{ $settings['city'] }}<br>
                    KVK: {{ $settings['kvk'] }}<br>
                    BTW: {{ $settings['btw-nummer'] }}<br>
                    Tel: {{ $settings['phone'] }}
                </p>
            </div>
        </div>

        <div class="terms">
            <p>Gelieve te betalen binnen {{ config('settings.paymentterm') }} dagen</p>
        </div>
    </div>
</div>
</body>
</html>
