<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Permissions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <form method="POST" action="{{ route('permission.update', $permission->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="py-2">
                            <x-admin.form.label for="name"
                                                class="{{$errors->has('name') ? 'text-red-400' : ''}}">{{ __('Name') }}</x-admin.form.label>

                            <x-admin.form.input id="name" class="{{$errors->has('name') ? 'border-red-400' : ''}}"
                                                type="text"
                                                name="name"
                                                value="{{ old('name', $permission->name) }}"
                            />
                        </div>

                        <div class="flex justify-end mt-4">
                            <x-admin.form.button>{{ __('Update') }}</x-admin.form.button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
