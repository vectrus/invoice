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


<div class="invoice"
     >

    {{--  {!! $template->html !!}--}}

    <div class="header">
        <br><br><br><br><br><br><br><br><br>
        <h1 id="header-title" >FAKTUUR</h1>
        <br><br>
        <div id="top">
            <div class="client-info">
                <div class="client-details">
                    <h2>Faktuuradres</h2>
                    <p class="client-name"><b> {{ $invoice->client->companyname }}</b><br>
                        {{ $invoice->client->address }}<br>
                        {{ $invoice->client->postalcode }} {{ $invoice->client->city }}</p>

                    @if($invoice->client->vat_number)
                        <p>BTW-nummer klant: {{ $invoice->client->vat_number }}</p>
                    @endif
                </div>
            </div>

            <div class="company-info" >
                <div>
                    <h2>Faktuurnummer</h2>
                    <p>{{ $invoice->invoice_number }}</p>


                    {{--<p>Van: <br/><b>{{ $settings['companyname'] }}</b><br>
                    {{ $settings['companyaddress'] }}<br>
                    {{ $settings['postcode'] }} {{ $settings['city'] }}<br>

                       KVK:  {{ $settings['kvk'] }}<br>
                        BTW: {{ $settings['btw-nummer'] }}<br><br>
                    Tel: {{ $settings['phone'] }}</p>--}}
                </div>
            </div>

            <div class="invoice-info" >
                <div>
                    <h2 style="width: 100%;">Faktuurdatum</h2>


                    <p style="width: 100%;">{{ $invoice->issue_date->format('d/m/Y') }}</p>
                    <p>Betalen voor: {{ $invoice->due_date->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>


    <div class="items-section" >

        <div style="padding: 10px;">

            <table class="table table-striped items-table w-100">
                <thead>
                <tr>
                    <th>Omschrijving</th>
                    <th class="text-right">Hv</th>
                    <th class="text-right">Stuksprijs</th>
                    <th class="text-right">BTW%</th>
                    {{--<th class="text-right">Subtotaal</th>--}}
                    <th class="text-right">BTW</th>
                    <th class="text-right">Totaal</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="text-right accent">{{ $item->quantity }}</td>
                        <td class="text-right">€{{ number_format($item->price, 2) }}</td>
                        <td class="text-right accent">{{ $item->tax_percentage }}%</td>
                        {{--<td class="text-right">€{{ number_format($item->calculateSubtotal(), 2) }}</td>--}}
                        <td class="text-right accent">€{{ number_format($item->calculateTax(), 2) }}</td>
                        <td class="text-right">€{{ number_format($item->calculateTotal(), 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            {{ $item->notes }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="6" class="text-right">&nbsp;</td>

                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="6" class="text-right">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right"><strong>Totaal te betalen:</strong></td>
                    <td class="text-right">€{{ number_format($invoice->amount_excl, 2) }}</td>
                    <td class="text-right">{{--€{{ number_format($invoice->getTotalTaxAttribute(), 2) }}--}}</td>
                    <td class="text-right">€{{ number_format($invoice->calculateTotal(), 2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>

    </div>

    <div class="footer">
        <div class="payment-info">
            <h4>Betaling via:</h4>
            <p><strong>Bank:</strong> {{ $settings['iban'] }}<br>
                <strong>Referentie:</strong> {{ $invoice->invoice_number }}</p>
        </div>

        {{-- {!! dd($invoice) !!}--}}

        <div class="terms">
            <p>Gelieve te betalen binnen {{ config('settings.paymentterm') }} dagen </p>
            <div class="min-h-screen py-8"
                 x-data="{
            showQRCode: false,
            invoice: @json($invoice),
            formatCurrency(amount) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'EUR'
                }).format(amount)
            }
         }">

                <div class="qrcode max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
                    <!-- QR Code Section -->
                    {{--<div x-show="showQRCode"
                         @click.away="showQRCode = false"
                         class="relative bg-white p-4 rounded-lg shadow-lg">
                        <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::size(150)
                                ->format('svg')
                                ->generate($invoice->generatePaymentUrl())) }}"
                             alt="Payment QR Code"
                             class="w-32 h-32">
                        <div class="mt-2 text-center text-sm text-gray-600">

                            <p class="font-medium" x-text="formatCurrency(invoice.total)"></p>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
