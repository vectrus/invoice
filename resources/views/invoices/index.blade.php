<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoices') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-12 gap-2">
                    @if(session('status'))
                        <div class="col-span-12 sm:px-2 lg:px-4">
                            <div class="alert alert-success mb-1 mt-1">
                                {{ session('status') }}
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="col-span-12 sm:px-2 lg:px-4">
                        <br>
                        <div class="pull-right mb-8">
                            <a class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2"
                               href="{{ route('dashboard') }}">Dashboard</a>
                            <a class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2"
                               href="{{ route('invoice.create') }}">New Invoice</a>
                        </div>
                    </div>

                    <!-- Success Messages -->
                    @if(session('status'))
                        <div class="col-span-12 sm:px-2 lg:px-4">
                            <div class="alert alert-success mb-1 mt-1">
                                {{ session('status') }}
                            </div>
                        </div>
                    @endif

                    <!-- Search Form -->
                    <div class="col-span-12 sm:px-2 lg:px-4">
                        <div class="flex">
                            <form method="GET" action="{{ route('invoice.index') }}">
                                <div class="flex border-2 rounded">
                                    <input type="text"
                                           name="search"
                                           class="px-4 py-2 w-80"
                                           placeholder="Search invoices..."
                                           value="{{ request('search') }}">
                                    <button type="submit" class="flex items-center justify-center px-4 border-l">
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

                        <!-- Invoices Table -->
                        <div class="inline-block min-w-full py-2 align-middle">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                <tr>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        {{ __('Invoice #') }}
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        {{ __('Client') }}
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        {{ __('Date') }}
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        {{ __('Amount Excl.') }}
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        {{ __('Amount Incl.') }}
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        {{ __('Status') }}
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900" colspan="3">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <a href="{{ route('invoice.edit', $invoice->id) }}"
                                               class="text-blue-600 hover:text-blue-900">
                                                {{ $invoice->invoice_number }}
                                            </a>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $invoice->client->companyname }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $invoice->issue_date->format('d/m/Y') }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            €{{ number_format($invoice->amount_excl, 2) }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            €{{ number_format($invoice->amount_incl, 2) }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            @if($invoice->status === 'paid')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Paid
                                                    </span>
                                            @elseif($invoice->status === 'sent')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Sent
                                                    </span>

                                                @if($invoice->status !== 'paid')
                                                    <form action="{{ route('invoice.reminder', $invoice) }}"
                                                          method="POST"
                                                          class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="text-yellow-600 hover:text-yellow-900 ml-2"
                                                                onclick="return confirm('Are you sure you want to send a reminder?')">
                                                            <i class="fas fa-envelope"></i> Herinnering E-mailen
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Draft
                                                    </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <a href="{{ route('invoice.show', $invoice->id) }}"
                                               class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <a href="{{ route('invoice.edit', $invoice->id) }}"
                                               class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <form action="{{ route('invoice.destroy', $invoice->id) }}"
                                                  method="POST"
                                                  class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Are you sure you want to delete this invoice?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            &nbsp; &nbsp; &nbsp; &nbsp;
                                            <a href="{{ route('invoice.email', $invoice->id) }}"
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-envelope"></i> Email PDF
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="col-span-12 sm:px-2 lg:px-4">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
