<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-800 leading-tight">
            {{ __('Producten') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-12 gap-2">
                    <div class="col-span-12 sm:px-2 lg:px-4">
                        <br>
                        <div class="pull-right mb-8">


                            <a class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2"
                               href="{{ route('dashboard') }}"> Dashboard</a>
                            {{--<a class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2"
                               href="{{ route('admin.invoices.export') }}"> Export XLS</a>--}}
                            <a class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2"
                               href="{{ route('invoice.create') }}"> Nieuwe factuur</a>
                        </div>

                    </div>
                    @if(session('status'))
                        <div class="alert alert-success mb-1 mt-1">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="col-span-12 sm:px-2 lg:px-4">

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        {{--<div class="pull-right mb-2">
                            <a class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                               href="{{ route('assets.create') }}"> Nieuwe huurder </a>
                        </div>--}}

                        <div class="flex ">
                            <form method="GET" action="{{ url('admin/invoices') }}">
                                @csrf
                                @method('get')
                                <div class="flex border-2 rounded">

                                    <input type="text" name="invoicesearch" class="px-4 py-2 w-80" placeholder="Zoek"
                                           value="{{ $invoicesearch }}">
                                    <button class="flex items-center justify-center px-4 border-l">
                                        <svg class="w-6 h-6 text-gray-600" fill="currentColor"
                                             xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 24 24">
                                            <path
                                                d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"/>
                                        </svg>
                                    </button>

                                </div>
                            </form>
                        </div>


                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                <tr>
                                    <th scope="col"
                                        class="hidden sm:table-cell py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">{{ __('Fact #')}}</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        @sortablelink('client_id', 'Naam')
                                    </th>


                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Datum')}}</th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Bedrag Excl')}}</th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Bedrad Incl')}}</th>

                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Betaald')}}</th>


                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"></th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"></th>


                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                {{ dd($invoices) }}
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td class="hidden sm:table-cell whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ $invoice->invoice->invoicenumber }} </td>

                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $invoice->invoice->client_id }} </td>


                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $invoice->invoicedate }} </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $invoice->amountexcl }} </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $invoice->amountincl }} </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $invoice->payed }} </td>


                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"><a
                                                href="mailto:{{ $invoice->email }}"><i
                                                    class="fa fa-2x fa-envelope text-blue-700"></i></a>
                                        </td>


                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <a

                                                href="{{ route('invoice.edit',$invoice->id) }}"><i
                                                    class="fa fa-2x fa-edit text-blue-700"></i></a>
                                        </td>

                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">

                                            <form action="{{ route('invoice.destroy',$invoice->id) }}" method="Post">

                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm(
                                            'Weet je zeker dat je deze factuur weg wilt annuleren?');"
                                                ><i class="fa  fa-trash text-blue-700"></i>Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <br><br>
                    <div class="col-span-12 sm:px-2 lg:px-4">
                        {!! $invoices->links() !!}
                    </div>
                    <br><br><br><br>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
