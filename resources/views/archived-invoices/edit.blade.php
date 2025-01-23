<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Old edit invoice archive') }}
            </h2>
        </div>
    </x-slot>
    <div class="container">
        <h1>Edit Archived Invoice</h1>

        <form action="{{ route('archived-invoices.update', $archivedInvoice) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="invoice_date">Invoice Date</label>
                <input type="date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror"
                       value="{{ old('invoice_date', $archivedInvoice->invoice_date->format('Y-m-d')) }}" required>
                @error('invoice_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="client_id">Client</label>
                <select name="client_id" class="form-control @error('client_id') is-invalid @enderror" required>
                    <option value="">Select Client</option>
                    @foreach($clients as $client)
                        <option
                            value="{{ $client->id }}" {{ (old('client_id', $archivedInvoice->client_id) == $client->id) ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="amount_incl">Amount (Including Tax)</label>
                <input type="number" step="0.01" name="amount_incl"
                       class="form-control @error('amount_incl') is-invalid @enderror"
                       value="{{ old('amount_incl', $archivedInvoice->amount_incl) }}" required>
                @error('amount_incl')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="amount_excl">Amount (Excluding Tax)</label>
                <input type="number" step="0.01" name="amount_excl"
                       class="form-control @error('amount_excl') is-invalid @enderror"
                       value="{{ old('amount_excl', $archivedInvoice->amount_excl) }}" required>
                @error('amount_excl')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="tax_amount">Tax Amount</label>
                <input type="number" step="0.01" name="tax_amount"
                       class="form-control @error('tax_amount') is-invalid @enderror"
                       value="{{ old('tax_amount', $archivedInvoice->tax_amount) }}" required>
                @error('tax_amount')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
                          required>{{ old('description', $archivedInvoice->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update Invoice</button>
            <a href="{{ route('archived-invoices.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-app-layout>
