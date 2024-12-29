<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $client->companyname }}'s Emails
            </h2>

            <div class="">
                <a class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 flex-end"
                   href="https://invoice.vectrus.nl/client"> Terug</a>
                <button
                    x-data=""
                    x-on:click="$dispatch('open-modal', 'send-email')"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Send New Email
                </button>
            </div>


        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($emails->isEmpty())
                        <p class="text-gray-500 text-center py-4">No emails found for this client.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subject
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Recipient
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($emails as $email)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $email->sent_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $email->subject }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $email->recipient_email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $email->status }}
                                                </span>


                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('client.emails.show', [$client, $email]) }}"
                                               class="text-indigo-600 hover:text-indigo-900">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $emails->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </div>


    {{--Modal --}}
    <x-modal name="send-email" :show="false" focusable>
        <form method="POST" action="{{ route('client.emails.store', ['client' => $client->id]) }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900">
                Send Email to {{ $client->companyname }}
            </h2>

            <div class="mt-6">
                <x-input-label for="recipient_email" value="Recipient Email"/>
                <x-text-input
                    id="recipient_email"
                    name="recipient_email"
                    type="email"
                    class="mt-1 block w-full"
                    value="{{$client->email}}"
                    required
                />
                <x-input-error :messages="$errors->get('recipient_email')" class="mt-2"/>
            </div>

            <div class="mt-6">
                <x-input-label for="subject" value="Subject"/>
                <x-text-input
                    id="subject"
                    name="subject"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <x-input-error :messages="$errors->get('subject')" class="mt-2"/>
            </div>

            <div class="mt-6">
                <x-input-label for="body" value="Message"/>
                <textarea
                    id="body"
                    name="body"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                    rows="6"
                    required
                ></textarea>
                <x-input-error :messages="$errors->get('body')" class="mt-2"/>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">
                    Cancel
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    Send Email
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
