<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Invoice') }} {{ $invoice->invoice_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-12 gap-2">
                    <!-- Success Messages -->
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

                    <div class="col-span-12 sm:px-2 lg:px-4">

<br>
                        <form action="{{ route('invoice.update', $invoice->id) }}" method="POST" x-data="{
                            items: {{ json_encode($invoice->items) }},
                            calculateSubtotal() {
                                return this.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
                            },
                            calculateTotalTax() {
                                return this.items.reduce((sum, item) => {
                                    const subtotal = item.quantity * item.price;
                                    return sum + (subtotal * (item.tax_percentage / 100));
                                }, 0);
                            },
                            calculateTotal() {
                                return this.calculateSubtotal() + this.calculateTotalTax();
                            },
                            formatPrice(amount) {
                                return '€' + Number(amount).toFixed(2);
                            },
                            addItem() {
                                this.items.push({
                                    name: '',
                                    quantity: 1,
                                    price: 0,
                                    tax_percentage: 21
                                });
                            },
                            removeItem(index) {
                                if (this.items.length > 1) {
                                    this.items.splice(index, 1);
                                }
                            }
                        }">
                            @csrf
                            @method('PUT')

                            <!-- Client Selection -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Client
                                </label>
                                <select name="client_id" class="border-gray-300 mt-1 block w-full" required>
                                    @foreach($clients as $client)
                                        <option
                                            value="{{ $client->id }}" {{ $client->id == $invoice->client_id ? 'selected' : '' }}>
                                            {{ $client->companyname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Dates and Status -->
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        Issue Date
                                    </label>
                                    <input type="date"
                                           name="issue_date"
                                           value="{{ $invoice->issue_date->format('Y-m-d') }}"
                                           class="form-input border-gray-300 mt-1 block w-full"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        Due Date
                                    </label>
                                    <input type="date"
                                           name="due_date"
                                           value="{{ $invoice->due_date->format('Y-m-d') }}"
                                           class="form-input border-gray-300 mt-1 block w-full"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        Status
                                    </label>
                                    <select name="status" class="border-gray-300 mt-1 block w-full">
                                        <option value="draft" :selected=" invoice.status == draft">
                                            Draft
                                        </option>
                                        <option value="sent" :selected=" invoice.status == sent">Sent
                                        </option>
                                        <option value="paid" :selected=" invoice.status == paid">Paid
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Invoice Items -->
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-bold">Invoice Items</h3>
                                    <button type="button"
                                            @click="addItem"
                                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                        Add Item
                                    </button>
                                </div>

                                <template x-for="(item, index) in items" :key="index">
                                    <div class="grid grid-cols-12 gap-4 mb-4 items-center bg-gray-50 p-4 rounded">
                                        <div class="col-span-4">
                                            <input type="text"
                                                   x-model="item.name"
                                                   :name="'items['+index+'][name]'"
                                                   class="form-input border-gray-300 mt-1 block w-full"
                                                   placeholder="Item name"
                                                   required>
                                        </div>
                                        <div class="col-span-2">
                                            <input type="number"
                                                   x-model="item.quantity"
                                                   :name="'items['+index+'][quantity]'"
                                                   class="form-input border-gray-300 mt-1 block w-full"
                                                   min="1"
                                                   required>
                                        </div>
                                        <div class="col-span-2">
                                            <input type="number"
                                                   x-model="item.price"
                                                   :name="'items['+index+'][price]'"
                                                   class="form-input border-gray-300 mt-1 block w-full"
                                                   step="0.01"
                                                   required>
                                        </div>
                                        <div class="col-span-2">
                                            <select x-model.number="item.tax_percentage"
                                                    :name="'items['+index+'][tax_percentage]'"
                                                    class="border-gray-300 mt-1 block w-full">
                                                <option value="6" :selected="item.tax_percentage == 6">6%</option>
                                                <option value="21" :selected="item.tax_percentage == 21">21%</option>
                                            </select>
                                        </div>
                                        <div class="col-span-1 text-right">
                                            <span
                                                x-text="formatPrice(item.quantity * item.price * (1 + item.tax_percentage/100))"></span>
                                        </div>
                                        <div class="col-span-1">
                                            <button type="button"
                                                    @click="removeItem(index)"
                                                    class="text-red-600 hover:text-red-800">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <!-- Totals -->
                                <div class="border-t border-gray-200 pt-4 mt-4">
                                    <div class="flex justify-end">
                                        <div class="w-64">
                                            <div class="flex justify-between mb-2">
                                                <span>Subtotal:</span>
                                                <span x-text="formatPrice(calculateSubtotal())"></span>
                                            </div>
                                            <div class="flex justify-between mb-2">
                                                <span>Tax:</span>
                                                <span x-text="formatPrice(calculateTotalTax())"></span>
                                            </div>
                                            <div class="flex justify-between font-bold border-t border-gray-200 pt-2">
                                                <span>Total:</span>
                                                <span x-text="formatPrice(calculateTotal())"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Notes
                                </label>
                                <textarea name="notes"
                                          class="form-textarea mt-1 block w-full"
                                          rows="3">{{ $invoice->notes }}</textarea>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('invoice.index') }}"
                                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    Cancel
                                </a>
                                <a href="{{ route('invoice.print', $invoice->id) }}"
                                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-print mr-2"></i>Print PDF
                                </a>
                                <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Update Invoice
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
