<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Edit Contact</h2>
                        <a href="{{ route('contacts.index') }}"
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to List
                        </a>
                    </div>

                    <form x-data="{
                              formData: {
                                  _token: '{{ csrf_token() }}',
                                  _method: 'PUT',
                                  firstname: '{{ old('firstname', $contact->firstname) }}',
                                  lastname: '{{ old('lastname', $contact->lastname) }}',
                                  email: '{{ old('email', $contact->email) }}',
                                  phonenumber: '{{ old('phonenumber', $contact->phonenumber) }}',
                                  contactinfo: '{{ old('contactinfo', $contact->contactinfo) }}',
                                  client_id: '{{ old('client_id', $contact->client_id) }}'
                              },
                              errors: {},
                              submitForm() {
                                  const formData = new FormData();
                                  for (const [key, value] of Object.entries(this.formData)) {
                                      formData.append(key, value);
                                  }

                                  fetch('{{ route('contacts.update', $contact) }}', {
                                      method: 'POST',
                                      headers: {
                                          'Accept': 'application/json',
                                      },
                                      body: formData
                                  })
                                  .then(response => response.json())
                                  .then(data => {
                                      if (data.errors) {
                                          this.errors = data.errors;
                                      } else {
                                          window.location.href = '{{ route('contacts.index') }}';
                                      }
                                  })
                                  .catch(error => {
                                      console.error('Error:', error);
                                  });
                              }
                          }"
                          @submit.prevent="submitForm">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div>
                                <label for="firstname" class="block text-sm font-medium text-gray-700">First
                                    Name</label>
                                <input type="text"
                                       id="firstname"
                                       x-model="formData.firstname"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <template x-if="errors.firstname">
                                    <p class="mt-1 text-sm text-red-600" x-text="errors.firstname[0]"></p>
                                </template>
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text"
                                       id="lastname"
                                       x-model="formData.lastname"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <template x-if="errors.lastname">
                                    <p class="mt-1 text-sm text-red-600" x-text="errors.lastname[0]"></p>
                                </template>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email"
                                       id="email"
                                       x-model="formData.email"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <template x-if="errors.email">
                                    <p class="mt-1 text-sm text-red-600" x-text="errors.email[0]"></p>
                                </template>
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="phonenumber" class="block text-sm font-medium text-gray-700">Phone
                                    Number</label>
                                <input type="tel"
                                       id="phonenumber"
                                       x-model="formData.phonenumber"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <template x-if="errors.phonenumber">
                                    <p class="mt-1 text-sm text-red-600" x-text="errors.phonenumber[0]"></p>
                                </template>
                            </div>

                            <!-- Contact Info -->
                            <div class="md:col-span-2">
                                <label for="contactinfo" class="block text-sm font-medium text-gray-700">Additional
                                    Information</label>
                                <textarea id="contactinfo"
                                          x-model="formData.contactinfo"
                                          rows="4"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                <template x-if="errors.contactinfo">
                                    <p class="mt-1 text-sm text-red-600" x-text="errors.contactinfo[0]"></p>
                                </template>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="client_id" class="block text-sm font-medium text-gray-700">Associated
                                Client</label>
                            <select id="client_id"
                                    x-model="formData.client_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select a client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ $contact->client_id == $client->id ? 'selected' : '' }}>
                                        {{ $client->companyname }}
                                    </option>
                                @endforeach
                            </select>
                            <template x-if="errors.client_id">
                                <p class="mt-1 text-sm text-red-600" x-text="errors.client_id[0]"></p>
                            </template>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('contacts.index') }}"
                               class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Update Contact
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
