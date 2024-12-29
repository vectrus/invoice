<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-800 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="">
        <div class="mx-auto sm:px-6 lg:px-8">
            <x-admin.message/>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
