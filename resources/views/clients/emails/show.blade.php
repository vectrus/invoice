<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Email Details
            </h2>
            <a class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 flex-end"
               href="https://invoice.vectrus.nl/client"> Terug</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="space-y-6 grid grid-cols-5">
                        <div class="col-span-5">
                            <h3 class="text-lg w-full font-medium text-gray-900">Subject</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ $email->subject }}</p>
                        </div>
                        <div class="col-span-5">
                            <hr/>
                        </div>
                        <div class="col-span-1">
                            <h3 class="text-lg font-medium text-gray-900">From</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ $email->sender_email }}</p>
                        </div>

                        <div class="col-span-1">
                            <h3 class="text-lg font-medium text-gray-900">To</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ $email->recipient_email }}</p>
                        </div>

                        <div class="col-span-1">
                            <h3 class="text-lg font-medium text-gray-900">Sent At</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ $email->sent_at->format('M d, Y H:i:s') }}</p>
                        </div>

                        <div class="col-span-1">
                            <h3 class="text-lg font-medium text-gray-900">Status</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ $email->status }}</p>
                        </div>

                        <div class="col-span-4">
                            <h3 class="text-lg font-medium text-gray-900">Message</h3>
                            <div class="mt-1 text-sm text-gray-600 whitespace-pre-wrap">{{ $email->body }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
