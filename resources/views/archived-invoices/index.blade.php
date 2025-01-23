<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Old invoice archive') }}
            </h2>
        </div>
    </x-slot>
    <div class="container">
        <h1>Archived Invoices</h1>
        @if(session('success'))
            <div class="col-span-12 sm:px-2 lg:px-4">
                <div class="alert alert-success mb-1 mt-1">
                    {{ session('success') }}
                </div>
            </div>
        @elseif(session('error'))
            <div class="col-span-12 sm:px-2 lg:px-4">
                <div class="alert alert-success mb-1 mt-1">
                    {{ session('error') }}
                </div>
            </div>
        @endif
        <div class="mb-4">
            <a href="{{ route('archived-invoices.create') }}" class="btn btn-primary">Create New</a>

            <!-- Import Form -->
            <form action="{{ route('archived-invoices.import') }}" method="POST" enctype="multipart/form-data"
                  class="mt-3">
                @csrf
                <div class="input-group">
                    <input type="file" name="csv_file" class="form-control" accept=".csv">
                    <button type="submit" class="btn btn-secondary">Import CSV</button>
                </div>
            </form>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th>Amount (Incl)</th>
                <th>Amount (Excl)</th>
                <th>Tax</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                    <td>{{ $invoice->client->name }}</td>
                    <td>{{ number_format($invoice->amount_incl, 2) }}</td>
                    <td>{{ number_format($invoice->amount_excl, 2) }}</td>
                    <td>{{ number_format($invoice->tax_amount, 2) }}</td>
                    <td>{{ Str::limit($invoice->description, 50) }}</td>
                    <td>
                        <a href="{{ route('archived-invoices.show', $invoice) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('archived-invoices.edit', $invoice) }}"
                           class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('archived-invoices.destroy', $invoice) }}" method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $invoices->links() }}
    </div>
</x-app-layout>>

