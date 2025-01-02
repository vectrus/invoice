<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoice') }} {{ $invoice->invoice_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-12 gap-2">
                    <!-- Existing header section -->
                    <div class="col-span-12 sm:px-2 lg:px-4">
                        <br>
                        <div class="pull-right mb-8">
                            <a class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 flex-end"
                               href="https://invoice.vectrus.nl/invoices"> Terug</a>
                            &nbsp;
                            {{--<a
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 flex-end"
                                href="/client/{{ $client->id }}/emails"><i
                                    class="fa fa-envelope text-orange-700"></i> {{ __('Client E-mails') }}</a>--}}
                        </div>
                    </div>
                </div>
                <div class="invoice">
                    <br><br>
                    <div class="header">

                        <div class="client-info">
                            <div class="client-details">

                                <p class="client-name">Aan:<br><b> {{ $invoice->client->companyname }}</b><br>
                                    {{ $invoice->client->address }}<br>
                                    {{ $invoice->client->postalcode }} {{ $invoice->client->city }}</p>

                                @if($invoice->client->vat_number)
                                    <p>BTW-nummer klant: {{ $invoice->client->vat_number }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="company-info">
                            <p>Van: <br/><b>{{ $settings['companyname'] }}</b><br>
                                {{ $settings['companyaddress'] }}<br>
                                {{ $settings['postcode'] }} {{ $settings['city'] }}<br>

                                KVK: {{ $settings['kvk'] }}<br>
                                BTW: {{ $settings['btw-nummer'] }}<br><br>
                                {{--  Tel: {{ $settings['phone'] }}--}}</p>
                        </div>
<br/>
                        <div class="invoice-info">


                            <table class="info-table">
                                <tr>
                                    <td><br><strong>Factuurnummer:</strong></td>
                                    <td><br>{{ $invoice->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Datum:</strong></td>
                                    <td>{{ $invoice->issue_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Betalen voor:</strong></td>
                                    <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bank:</strong></td>
                                    <td> {{ $settings['iban'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Referentie:</strong></td>
                                    <td>{{ $invoice->invoice_number }}</td>
                                </tr>


                            </table>
                        </div>
                    </div>
                    <div class="spacer">
                        <br/>

                    </div>
                    <div class="items-section">
                        <table class="table table-striped items-table w-100">
                            <thead>
                            <tr >
                                <th>Omschrijving</th>
                                <th class="text-right">Hv</th>
                                <th class="text-right">Stuksprijs</th>
                                <th class="text-right">BTW%</th>
                                <th class="text-right">Subtotaal</th>
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
                                    <td class="text-right">€{{ number_format($item->calculateSubtotal(), 2) }}</td>
                                    <td class="text-right accent">€{{ number_format($item->calculateTax(), 2) }}</td>
                                    <td class="text-right">€{{ number_format($item->calculateTotal(), 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="8" class="text-right">&nbsp;</td>

                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Totaal te betalen:</strong></td>
                                <td class="text-right">€{{ number_format($invoice->amount_excl, 2) }}</td>
                                <td class="text-right">€{{ number_format($invoice->total_tax, 2) }}</td>
                                <td class="text-right">€{{ number_format($invoice->calculateTotal(), 2) }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="footer">
                        <div class="payment-info">
                            <h4>Betaling via:</h4>
                            <p><strong>Bank:</strong> {{ $settings['iban'] }}<br>
                                <strong>Referentie:</strong> {{ $invoice->invoice_number }}</p>
                        </div>

                        {{-- {!! dd($invoice) !!}--}}

                        <div class="terms">
                            <p>Gelieve te betalen binnen {{ $invoice->due_date->diffInDays($invoice->issue_date) }}
                                dagen</p>
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
                                    <div x-show="showQRCode"
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
