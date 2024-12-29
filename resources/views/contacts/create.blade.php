<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Create New Contact</h2>

                    <form action="{{ route('contacts.store') }}"
                          method="POST"
                          x-data="{
                              formData: {
                                  _token: '{{ csrf_token() }}',
                                  firstname: '',
                                  lastname: '',
                                  email: '',
                                  phonenumber: '',
                                  contactinfo: '',
                                  client_id: ''
                              },
                              errors: {},
                              submitForm() {
                                  const formData = new FormData();
                                  for (const [key, value] of Object.entries(this.formData)) {
                                      formData.append(key, value);
                                  }

                                  fetch('{{ route('contacts.store') }}', {
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

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text"
                                       x-model="formData.firstname"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <template x-if="errors.firstname">
                                    <span x-text="errors.firstname[0]" class="text-red-500 text-sm"></span>
                                </template>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text"
                                       x-model="formData.lastname"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <template x-if="errors.lastname">
                                    <span x-text="errors.lastname[0]" class="text-red-500 text-sm"></span>
                                </template>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email"
                                       x-model="formData.email"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <template x-if="errors.email">
                                    <span x-text="errors.email[0]" class="text-red-500 text-sm"></span>
                                </template>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="tel"
                                       x-model="formData.phonenumber"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <template x-if="errors.phonenumber">
                                    <span x-text="errors.phonenumber[0]" class="text-red-500 text-sm"></span>
                                </template>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Additional Information</label>
                            <textarea x-model="formData.contactinfo"
                                      rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <template x-if="errors.contactinfo">
                                <span x-text="errors.contactinfo[0]" class="text-red-500 text-sm"></span>
                            </template>
                        </div>

                        <div class="mt-6">
                            <label for="client_id" class="block text-sm font-medium text-gray-700">Associated
                                Client</label>
                            <select id="client_id"
                                    x-model="formData.client_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select a client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->companyname }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.client_id">
                                <p class="mt-1 text-sm text-red-600" x-text="errors.client_id[0]"></p>
                            </template>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <a href="{{ route('contacts.index') }}"
                               class="bg-gray-200 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Contact
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
