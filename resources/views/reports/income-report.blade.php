<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Income report periodic') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-2">

                <div class="col-span-12 sm:px-2 lg:px-4">
                    <br>
                    <div class="pull-right mb-8">
                        <a class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 flex-end"
                           href="income"> Terug</a>
                        &nbsp;

                    </div>
                </div>

                <table class="table-auto w-full">
                    <thead>
                    <tr class="text-left">
                        <th>Fact. Nummer</th>
                        <th>Datum</th>
                        <th>Client</th>
                        <th class="text-right">Excl</th>
                        <th class="text-right">Incl</th>
                        <th class="text-right">btw</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoices as $invoice)
                        <tr >
                            <td>{{ $invoice->invoice_number }}</td>

                            <td>{!! \Carbon\Carbon::parse($invoice->issue_date)->format('d-m-Y') !!}</td>
                            <td>{{ $invoice->client->companyname }}</td>
                            <td class="text-right">{{ number_format($invoice->amount_excl, 2) }}</td>
                            <td class="text-right">{{ number_format($invoice->amount_incl, 2) }}</td>
                            <td class="text-right">{{ number_format($invoice->amount_incl - $invoice->amount_excl , 2)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
<br>
                <h2>Totaal incl.: € {{ number_format($total_income, 2) }}</h2>
                <h2>Totaal btw: € {{ number_format($total_income - $total_subtotal, 2) }}</h2>
                <h2>Totaal excl.: € {{ number_format($total_subtotal, 2) }}</h2>
            </div>
        </div>
    </div>
</x-app-layout>
