<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Old invoice archive') }}
            </h2>
        </div>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Invoice Details</h1>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 font-weight-bold">Invoice Date:</div>
                    <div class="col-md-9">{{ $archivedInvoice->invoice_date->format('Y-m-d') }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 font-weight-bold">Client:</div>
                    <div class="col-md-9">{{ $archivedInvoice->client->name }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 font-weight-bold">Amount (Including Tax):</div>
                    <div class="col-md-9">{{ number_format($archivedInvoice->amount_incl, 2) }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 font-weight-bold">Amount (Excluding Tax):</div>
                    <div class="col-md-9">{{ number_format($archivedInvoice->amount_excl, 2) }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 font-weight-bold">Tax Amount:</div>
                    <div class="col-md-9">{{ number_format($archivedInvoice->tax_amount, 2) }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 font-weight-bold">Description:</div>
                    <div class="col-md-9">{{ $archivedInvoice->description }}</div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('archived-invoices.edit', $archivedInvoice) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('archived-invoices.index') }}" class="btn btn-secondary">Back to List</a>
                <form action="{{ route('archived-invoices.destroy', $archivedInvoice) }}" method="POST"
                      class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
