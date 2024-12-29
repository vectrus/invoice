<div>
    {{--<label for="small" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Small
        select</label>
    <select id="small"
            class="block w-full p-2 mb-6 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <option selected>Choose a country</option>

    </select>--}}


    <div class="relative">
        <input
            type="text"
            class="form-input"
            placeholder="Search Contacts..."
            wire:model="query"
            wire:keydown.escape="reset"
            wire:keydown.tab="reset"
            wire:keydown.arrow-up="decrementHighlight"
            wire:keydown.arrow-down="incrementHighlight"
            wire:keydown.enter="selectContact"
        />

        <div wire:loading class="absolute z-10 w-full bg-white rounded-t-none shadow-lg list-group">
            <div class="list-item">Searching...</div>
        </div>

        @if(!empty($query))
            <div class="fixed top-0 bottom-0 left-0 right-0" wire:click="reset"></div>

            <div class="absolute z-10 w-full bg-white rounded-t-none shadow-lg list-group">
                @if(!empty($contacts))
                    @foreach($contacts as $i => $contact)
                        <div
                            {{--href="{{ route('show-contact', $contact['id']) }}"--}}
                            class="list-item {{ $highlightIndex === $i ? 'highlight' : '' }}"
                        >{{ $contact['firstname'] }} {{ $contact['lastname'] }}</div>
                    @endforeach
                @else
                    <div class="list-item">No results!</div>
                @endif
            </div>
        @endif
    </div>
</div>
