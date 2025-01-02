<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Client') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <!-- Left side info panel - keeping as is -->
            <div class="md:col-span-3">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('Nieuwe Klant') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Voer de gegevens van de nieuwe klant in.
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-3">
                <form method="POST" action="{{ route('client.store') }}"
                      x-data="{
                          showInvoiceAddress: false,
                          selectedContacts: [],
                          primaryContactId: null
                      }">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid grid-cols-6 gap-6">


                                <!-- New Contacts Section - Add this before the invoice address toggle -->
                                <div class="col-span-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Contactpersonen') }}
                                    </label>

                                    <select id="contacts-select"
                                            name="contact_ids[]"
                                            multiple
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach($contacts as $contact)
                                            <option value="{{ $contact->id }}">
                                                {{ $contact->firstname }} {{ $contact->lastname }}
                                                ({{ $contact->email }})
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- Primary Contact Selection -->
                                    <div class="mt-4" x-show="selectedContacts.length > 0">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('Primair contactpersoon') }}
                                        </label>
                                        <select name="primary_contact_id"
                                                x-model="primaryContactId"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">{{ __('Selecteer primair contact') }}</option>
                                            <template x-for="contactId in selectedContacts" :key="contactId">
                                                <option :value="contactId"
                                                        x-text="document.querySelector(`#contacts-select option[value='${contactId}']`).textContent">
                                                </option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <label for="companyname"
                                           class="block text-sm font-medium text-gray-700">{{ __('Bedrijfsnaam') }}</label>
                                    <input type="text" name="companyname" id="companyname"
                                           value="{{ old('companyname') }}"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md
                                    @error('companyname') border-red-500 @enderror">
                                    @error('companyname')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="col-span-6">
                                    <label for="address"
                                           class="block text-sm font-medium text-gray-700">{{ __('Adres') }}</label>
                                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md
                                    @error('address') border-red-500 @enderror">
                                    @error('address')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Postal Code -->
                                <div class="col-span-6 sm:col-span-2">
                                    <label for="postalcode"
                                           class="block text-sm font-medium text-gray-700">{{ __('Postcode') }}</label>
                                    <input type="text" name="postalcode" id="postalcode" value="{{ old('postalcode') }}"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md
                                    @error('postalcode') border-red-500 @enderror">
                                    @error('postalcode')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- City -->
                                <div class="col-span-6 sm:col-span-4">
                                    <label for="city"
                                           class="block text-sm font-medium text-gray-700">{{ __('Stad') }}</label>
                                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md
                                    @error('city') border-red-500 @enderror">
                                    @error('city')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="phonenumber"
                                           class="block text-sm font-medium text-gray-700">{{ __('Telefoonnummer') }}</label>
                                    <input type="tel" name="phonenumber" id="phonenumber"
                                           value="{{ old('phonenumber') }}"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md
                                    @error('phonenumber') border-red-500 @enderror">
                                    @error('phonenumber')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Mobile -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="mobile"
                                           class="block text-sm font-medium text-gray-700">{{ __('Mobiel') }}</label>
                                    <input type="tel" name="mobile" id="mobile" value="{{ old('mobile') }}"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>

                                <!-- Email -->
                                <div class="col-span-6 sm:col-span-4">
                                    <label for="email"
                                           class="block text-sm font-medium text-gray-700">{{ __('E-mail') }}</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md
                                    @error('email') border-red-500 @enderror">
                                    @error('email')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Toggle Invoice Address -->
                                <div class="col-span-6">
                                    <div class="flex items-center">
                                        <button type="button"
                                                @click="showInvoiceAddress = !showInvoiceAddress"
                                                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium focus:outline-none">
                                            <span
                                                x-text="showInvoiceAddress ? 'Verberg factuuradres' : 'Toon factuuradres'"></span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Invoice Address Fields -->
                                <div class="col-span-6" x-show="showInvoiceAddress">
                                    <div class="grid grid-cols-6 gap-6">
                                        <div class="col-span-6">
                                            <label for="invoiceaddress"
                                                   class="block text-sm font-medium text-gray-700">{{ __('Factuuradres') }}</label>
                                            <input type="text" name="invoiceaddress" id="invoiceaddress"
                                                   value="{{ old('invoiceaddress') }}"
                                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>

                                        <div class="col-span-6 sm:col-span-2">
                                            <label for="invoicepostalcode"
                                                   class="block text-sm font-medium text-gray-700">{{ __('Factuur Postcode') }}</label>
                                            <input type="text" name="invoicepostalcode" id="invoicepostalcode"
                                                   value="{{ old('invoicepostalcode') }}"
                                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>

                                        <div class="col-span-6 sm:col-span-4">
                                            <label for="invoicecity"
                                                   class="block text-sm font-medium text-gray-700">{{ __('Factuur Stad') }}</label>
                                            <input type="text" name="invoicecity" id="invoicecity"
                                                   value="{{ old('invoicecity') }}"
                                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                </div>

                                <!-- Memo -->
                                <div class="col-span-6">
                                    <label for="memo"
                                           class="block text-sm font-medium text-gray-700">{{ __('Memo') }}</label>
                                    <textarea name="memo" id="memo" rows="3"
                                              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('memo') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <a href="{{ route('client.index') }}"
                               class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Annuleren') }}
                            </a>
                            <button type="submit"
                                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Opslaan') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add this script section at the bottom of your blade -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#contacts-select').select2({
                placeholder: 'Zoek en selecteer contactpersonen',
                allowClear: true,
                multiple: true,
                width: '100%'
            }).on('change', function (e) {
                // Update Alpine.js data when selection changes
                let selected = $(this).val();
                let alpineComponent = Alpine.raw(this.closest('[x-data]').__x.$data);
                alpineComponent.selectedContacts = selected || [];

                // If the primary contact is not in the selection anymore, reset it
                if (selected && !selected.includes(alpineComponent.primaryContactId)) {
                    alpineComponent.primaryContactId = '';
                }
            });

            // Initialize Select2 with any existing data
            let initialContacts = @json(old('contact_ids', []));
            if (initialContacts.length > 0) {
                $('#contacts-select').val(initialContacts).trigger('change');
            }
        });
    </script>
</x-app-layout>
