<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Contacts</h2>
                        <a href="{{ route('contacts.create') }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Contact
                        </a>
                    </div>

                    <div x-data="{
                        search: '',
                        contacts: {{ Js::from($paginatedContacts->items()) }},
                        filteredContacts() {
                            return this.contacts.filter(contact =>
                                contact.firstname.toLowerCase().includes(this.search.toLowerCase()) ||
                                contact.lastname.toLowerCase().includes(this.search.toLowerCase()) ||
                                contact.email.toLowerCase().includes(this.search.toLowerCase())
                            )
                        }
                    }">
                        <div class="mb-4">
                            <input type="text"
                                   x-model="search"
                                   placeholder="Search contacts..."
                                   class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Phone
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="contact in filteredContacts()" :key="contact.id">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"
                                             x-text="contact.firstname + ' ' + contact.lastname"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900" x-text="contact.email"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900" x-text="contact.phonenumber"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a :href="`/contacts/${contact.id}`"
                                           class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        <a :href="`/contacts/${contact.id}/edit`"
                                           class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <button
                                            @click="if(confirm('Are you sure?')) document.getElementById(`delete-form-${contact.id}`).submit()"
                                            class="text-red-600 hover:text-red-900">Delete
                                        </button>
                                        <form :id="`delete-form-${contact.id}`" :action="`/contacts/${contact.id}`"
                                              method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
