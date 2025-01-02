<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Income report periodic') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-2">
                <form action="{{ route('generate-pdf') }}" method="POST"
                      class="flex justify-between space-x-2">
                    @csrf
                    @method('POST')
                    <label for="start_date"
                           class="block text-gray-700 text-sm font-bold mb-2">Start Date:</label>
                    <input type="date" id="start_date" name="start_date"
                           value="{{ old('start_date') }}"
                           class="form-input border-gray-300 mt-1 block w-full"
                           required>

                    <label for="end_date"
                           class="block text-gray-700 text-sm font-bold mb-2">End Date:</label>
                    <input type="date" id="end_date" name="end_date"
                           value="{{ old('end_date') }}"
                           class="form-input border-gray-300 mt-1 block w-full"
                           required>
                    <br><br>
                    <button
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        type="submit">Generate Report</button>
                    {{--<a
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded justify-end"
                        href="{{ route('generate-pdf') }}">Download PDF Report</a>--}}
                </form>


            </div>
        </div>
    </div>
</x-app-layout>
