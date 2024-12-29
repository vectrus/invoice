<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-800 leading-tight">
            {{ __('Bewerk klant') }}
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
                               href="https://invoice.vectrus.nl/client"> Terug</a>
                            &nbsp;
                            <a
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 flex-end"
                                href="/client/{{ $client->id }}/emails"><i
                                    class="fa fa-envelope text-orange-700"></i> {{ __('Client E-mails') }}</a>
                        </div>
                    </div>

                    <!-- Status messages -->
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

                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <!-- Contacts Section -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Contactpersonen</h3>

                                <!-- Add Contact Button -->
                                <div class="mb-4">
                                    <a href="{{ route('contacts.create', ['client_id' => $client->id]) }}"
                                       class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                        Nieuwe contactpersoon
                                    </a>
                                </div>

                                <!-- Contacts Table -->
                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg mb-6">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                                                Naam
                                            </th>
                                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Email
                                            </th>
                                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Telefoon
                                            </th>
                                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Primair
                                            </th>
                                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Acties
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach($client->contacts as $contact)
                                            <tr>
                                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                                    {{ $contact->firstname }} {{ $contact->lastname }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $contact->email }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $contact->phonenumber }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    <input type="radio"
                                                           name="primary_contact"
                                                           value="{{ $contact->id }}"
                                                           {{ $client->primary_contact_id == $contact->id ? 'checked' : '' }}
                                                           data-client-id="{{ $client->id }}"
                                                           onchange="updatePrimaryContact({{ $client->id }}, {{ $contact->id }})"
                                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    <a href="{{ route('contacts.edit', $contact->id) }}"
                                                       class="text-indigo-600 hover:text-indigo-900">Bewerk</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Client Edit Form -->
                            <form method="POST" action="{{ route('client.update', $client->id) }}"
                                  enctype='multipart/form-data'>
                                @csrf
                                @method('PUT')



                                <div class="mb-6">
                                    <label for="companyname"
                                           class="block text-sm font-medium leading-6 text-gray-900">Bedrijfsnaam</label>
                                    <input
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        name="companyname" type="text"
                                        value="{{ old('companyname', $client->companyname)}}">
                                    @if ($errors->has('companyname'))
                                        <p class="formfield__message error">{{ $errors->first('companyname') }}</p>
                                    @endif
                                </div>


                                {{--<div>
                                    <label for="contact-select"
                                           class="block text-sm font-medium leading-6 text-gray-900">Primair contactpersoon</label>
                                    <select id="contact-select"
                                            name="primary_contact_id"
                                            class="mt-2 block w-full text-sm rounded-md border-0 py-2.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            >
                                        <option value="">Selecteer contact</option>
                                    </select>
                                </div>--}}

                                <script>
                                    $(document).ready(function () {
                                        $('#contact-select').select2({
                                            placeholder: 'Zoek contactpersoon',
                                            minimumInputLength: 2,
                                            ajax: {
                                                url: '{{ route("contacts.search") }}',
                                                dataType: 'json',
                                                delay: 250,
                                                processResults: function (data) {
                                                    return {
                                                        results: $.map(data, function (item) {
                                                            return {
                                                                text: item.firstname + ' (' + item.email + ')',
                                                                id: item.id
                                                            }
                                                        })
                                                    };
                                                },
                                                cache: true
                                            }
                                        });
                                        // If you have a pre-selected value (e.g., for editing a form)
                                        // {{ old('companyname', $client->primary_contact_id)}}

                                        var preSelectedId = '{{ $client->primary_contact_id ?? "" }}'; // Assuming you pass this from your controller
                                        if (preSelectedId) {
                                            $.ajax({
                                                url: '{{ url("contacts") }}/' + preSelectedId,
                                                dataType: 'json'
                                            }).then(function (data) {
                                                // Create the option and append it to the select
                                                var option = new Option(data.firstname + ' (' + data.email + ')', data.id, true, true);
                                                $('#contact-select').append(option).trigger('change');
                                            });
                                        }

                                        // Optional: Log the selected value when it changes
                                        $('#contact-select').on('select2:select', function (e) {
                                            var data = e.params.data;
                                            console.log('Selected contact:', data.text, 'with id:', data.id);
                                        });
                                    });
                                </script>

                                <div class="mb-6">
                                    <label for="address"
                                           class="block text-sm font-medium leading-6 text-gray-900">Adres</label>
                                    <input
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        name="address" type="text"
                                        value="{{ old('address', $client->address)}}">
                                    @if ($errors->has('address'))
                                        <p class="formfield__message error">{{ $errors->first('address') }}</p>
                                    @endif
                                </div>

                                <div class="mb-6">
                                    <label for="postalcode"
                                           class="block text-sm font-medium leading-6 text-gray-900">Postcode</label>
                                    <input
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        name="postalcode" type="text"
                                        value="{{ old('postalcode', $client->postalcode)}}">
                                    @if ($errors->has('postalcode'))
                                        <p class="formfield__message error">{{ $errors->first('postalcode') }}</p>
                                    @endif
                                </div>

                                <div class="mb-6">
                                    <label for="city"
                                           class="block text-sm font-medium leading-6 text-gray-900">Plaats</label>
                                    <input
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        name="city" type="text"
                                        value="{{ old('city', $client->city)}}">
                                    @if ($errors->has('city'))
                                        <p class="formfield__message error">{{ $errors->first('city') }}</p>
                                    @endif
                                </div>

                                <div class="mb-6">
                                    <label for="phonenumber"
                                           class="block text-sm font-medium leading-6 text-gray-900">Telefoonnummer</label>
                                    <input
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        name="phonenumber" type="text"
                                        value="{{ old('phonenumber', $client->phonenumber)}}">
                                    @if ($errors->has('phonenumber'))
                                        <p class="formfield__message error">{{ $errors->first('phonenumber') }}</p>
                                    @endif
                                </div>

                                <div class="mb-6">
                                    <label for="mobile"
                                           class="block text-sm font-medium leading-6 text-gray-900">Mobiel nummer</label>
                                    <input
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        name="mobile" type="text"
                                        value="{{ old('mobile', $client->mobile)}}">
                                    @if ($errors->has('mobile'))
                                        <p class="formfield__message error">{{ $errors->first('mobile') }}</p>
                                    @endif
                                </div>

                                <div class="mb-6">
                                    <label for="email"
                                           class="block text-sm font-medium leading-6 text-gray-900">E-Mail</label>
                                    <input
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        name="email" type="text"
                                        value="{{ old('email', $client->email)}}">
                                    @if ($errors->has('email'))
                                        <p class="formfield__message error">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>

                                <div class="mb-6">
                                    <label for="invoiceaddress"
                                           class="block text-sm font-medium leading-6 text-gray-900">Factuur adres</label>
                                    <input
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        name="invoiceaddress" type="text"
                                        value="{{ old('invoiceaddress', $client->invoiceaddress)}}">
                                    @if ($errors->has('invoiceaddress'))
                                        <p class="formfield__message error">{{ $errors->first('invoiceaddress') }}</p>
                                    @endif
                                </div>

                                <div class="mb-6">
                                    <label for="invoicepostalcode"
                                           class="block text-sm font-medium leading-6 text-gray-900">Factuur postcode</label>
                                    <input
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        name="invoicepostalcode" type="text"
                                        value="{{ old('invoicepostalcode', $client->postalcode)}}">
                                    @if ($errors->has('invoicepostalcode'))
                                        <p class="formfield__message error">{{ $errors->first('invoicepostalcode') }}</p>
                                    @endif
                                </div>

                                <div class="mb-6">
                                    <label for="invoicecity"
                                           class="block text-sm font-medium leading-6 text-gray-900">Factuur plaats</label>
                                    <input
                                        class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        name="invoicecity" type="text"
                                        value="{{ old('invoicecity', $client->invoicecity)}}">
                                    @if ($errors->has('invoicecity'))
                                        <p class="formfield__message error">{{ $errors->first('invoicecity') }}</p>
                                    @endif
                                </div>


                                <div class="mb-6">
                                    <label for="memo"
                                           class="block text-sm font-medium leading-6 text-gray-900">Memo
                                        </label>
                                    <textarea
                                        class="mt-2 block w-full h-64 rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        name="memo" type="text"
                                        >{{ old('memo', $client->memo)}}</textarea>
                                    @if ($errors->has('memo'))
                                        <p class="formfield__message error">{{ $errors->first('memo') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <input type="submit"
                                           class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2"
                                           name="submit"
                                    >
                                </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this script section at the bottom of your blade -->
    <script>
        function updatePrimaryContact(clientId, contactId) {
            fetch(`/client/${clientId}/update-primary-contact`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    contact_id: contactId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Optionally show a success message
                        console.log('Primary contact updated successfully');
                    }
                })
                .catch(error => {
                    console.error('Error updating primary contact:', error);
                });
        }

        // Keep your existing Select2 initialization
        $(document).ready(function () {
            $('#contact-select').select2({
                // Your existing Select2 configuration
            });
        });
    </script>
</x-app-layout>
