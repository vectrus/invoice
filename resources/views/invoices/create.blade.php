<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Invoice') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"
             x-data="{
                options: [],
                items: [{
                    name: '',
                    description: '',
                    quantity: 1,
                    price: 0,
                    tax_percentage: 21
                }],
                selectedValue: null,
                showClientModal: false,
                newClient: {
                    name: '',
                    email: '',
                    phone: '',
                    address: '',
                    vat_number: ''
                },
                addItem() {
                    this.items.push({
                        name: '',
                        description: '',
                        quantity: 1,
                        price: 0,
                        tax_percentage: 21
                    });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                calculateItemTotal(item) {
                    const subtotal = item.quantity * item.price;
                    const tax = subtotal * (item.tax_percentage / 100);
                    return subtotal + tax;
                },
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
               async saveClient() {
                    try {
                        const response = await fetch('/client/quickStore', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                            },
                            body: JSON.stringify(this.newClient)
                        });

                        if (response.ok) {
                            const client = await response.json();

                            // Get reference to dropdown component
                            const dropdownEl = this.$refs.clientDropdown;
                            if (dropdownEl && dropdownEl.__x) {
                                dropdownEl.__x.$data.addNewOption(client);
                            }

                            // Reset form and close modal
                            this.newClient = {
                                companyname: '',
                                email: '',
                                phonenumber: '',
                                address: '',
                                vat_number: ''
                            };
                            this.showClientModal = false;
                        }
                    } catch (error) {
                        console.log(error);
                    }
                }
             }">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('invoice.store') }}" method="POST">
                        @csrf

                        <!-- Client Selection -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center">
                                <label class="block text-sm font-medium text-gray-700">Client</label>
                                <button type="button"
                                        @click="showClientModal = true"
                                        class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm">
                                    Add New Client
                                </button>
                            </div>
                            <div x-data="dropdown()" x-ref="clientDropdown" class="relative">

                                <input type="hidden" name="client_id" x-model="selectedValue" required>

                                <button type="button"
                                        @click="open = !open"
                                        @click.away="open = false"
                                        class="mt-1 relative w-full cursor-default rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 text-left shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    <span class="block truncate" x-text="selectedText || 'Select Client'"></span>
                                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                      d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                      clip-rule="evenodd"/>
            </svg>
        </span>
                                </button>

                                <div x-show="open"
                                     x-transition
                                     class="absolute z-10 mt-1 w-full rounded-md bg-white shadow-lg">
                                    <div class="p-2">
                                        <input type="text"
                                               x-model="search"
                                               @keydown.escape.window="open = false"
                                               placeholder="Search clients..."
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <ul class="max-h-60 overflow-auto py-1">
                                        <template x-for="client in filteredOptions" :key="client.id">
                                            <li @click="selectOption(client)"
                                                x-text="client.name"
                                                class="relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white"
                                                :class="{ 'bg-indigo-600 text-white': client.id === selectedValue }">
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>

                            <script>
                                function dropdown() {
                                    return {
                                        open: false,
                                        search: '',
                                        selectedValue: '',
                                        selectedText: '',
                                        options: @json($clients->map(fn($client) => [
            'id' => $client->id,
            'name' => $client->companyname
        ])),

                                        init() {
                                            // Make options accessible to parent scope
                                            this.$parent.options = this.options;
                                        },

                                        get filteredOptions() {
                                            return this.search === ''
                                                ? this.options
                                                : this.options.filter(client =>
                                                    client.name.toLowerCase().includes(this.search.toLowerCase())
                                                );
                                        },

                                        selectOption(client) {
                                            this.selectedValue = client.id;
                                            this.selectedText = client.name;
                                            this.open = false;
                                            this.search = '';
                                        },

                                        addNewOption(client) {
                                            this.options.push({
                                                id: client.id,
                                                name: client.companyname
                                            });
                                            this.selectOption({
                                                id: client.id,
                                                name: client.companyname
                                            });
                                        }
                                    }
                                }                </script>
                        </div>

                        <!-- Invoice Details -->
                        <div class="grid grid-cols-3 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Issue Date</label>
                                <input type="date" name="issue_date" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Due Date</label>
                                <input type="date" name="due_date" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="draft">Draft</option>
                                    <option value="sent">Sent</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Invoice Items</h3>
                                <button type="button" @click="addItem"
                                        class="bg-green-500 text-white px-3 py-1 rounded-md text-sm">
                                    Add Item
                                </button>
                            </div>

                            <template x-for="(item, index) in items" :key="index">
                                <div class="grid grid-cols-12 gap-4 mb-4 items-start">
                                    <div class="col-span-4">
                                        <input type="text" x-model="item.name" :name="'items['+index+'][name]'"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="Item name" required>
                                        <textarea x-model="item.description" :name="'items['+index+'][description]'"
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                  rows="2" placeholder="Description"></textarea>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="number" x-model="item.quantity"
                                               :name="'items['+index+'][quantity]'"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               min="1" required>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="number" x-model="item.price" :name="'items['+index+'][price]'"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               step="0.01" min="0" required>
                                    </div>
                                    <div class="col-span-2">
                                        <select x-model="item.tax_percentage" :name="'items['+index+'][tax_percentage]'"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="6">6%</option>
                                            <option value="21">21%</option>
                                        </select>
                                    </div>
                                    <div class="col-span-1 text-right"
                                         x-text="formatPrice(calculateItemTotal(item))"></div>
                                    <div class="col-span-1">
                                        <button type="button" @click="removeItem(index)"
                                                class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <!-- Totals -->
                            <div class="border-t pt-4 mt-6">
                                <div class="flex justify-end space-y-2 text-sm">
                                    <div class="w-64 space-y-2">
                                        <div class="flex justify-between">
                                            <span>Subtotal:</span>
                                            <span x-text="formatPrice(calculateSubtotal())"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Tax:</span>
                                            <span x-text="formatPrice(calculateTotalTax())"></span>
                                        </div>
                                        <div class="flex justify-between font-bold border-t pt-2">
                                            <span>Total:</span>
                                            <span x-text="formatPrice(calculateTotal())"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" rows="3"
                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('invoice.index') }}"
                               class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                Create Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- New Client Modal -->
            <div x-show="showClientModal"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center"
                 @click.self="showClientModal = false">
                <div class="bg-white rounded-lg p-6 max-w-md w-full">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Client</h3>
                    <form @submit.prevent="saveClient">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" x-model="newClient.companyname" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" x-model="newClient.email"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" x-model="newClient.phonenumber"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea x-model="newClient.address" rows="2"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">VAT Number</label>
                                <input type="text" x-model="newClient.vat_number"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-2">
                            <button type="button"
                                    @click="showClientModal = false"
                                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                Save Client
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
