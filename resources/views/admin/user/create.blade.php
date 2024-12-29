<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="shadow sm:rounded-lg">
                <x-admin.breadcrumb href="{{route('user.index')}}"
                                    title="{{ __('Create user') }}">{{ __('<< Back to all users') }}</x-admin.breadcrumb>
                <x-admin.form.errors/>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                <form method="POST" action="{{ route('user.store') }}">
                    @csrf

                    <div class="py-2">
                        <x-admin.form.label for="name"
                                            class="{{$errors->has('name') ? 'text-red-400' : ''}}">{{ __('Name') }}</x-admin.form.label>

                        <x-admin.form.input id="name" class="{{$errors->has('name') ? 'border-red-400' : ''}}"
                                            type="text"
                                            name="name"
                                            value="{{ old('name') }}"
                        />
                    </div>

                    <div class="py-2">
                        <x-admin.form.label for="email"
                                            class="{{$errors->has('email') ? 'text-red-400' : ''}}">{{ __('Email') }}</x-admin.form.label>

                        <x-admin.form.input id="email" class="{{$errors->has('email') ? 'border-red-400' : ''}}"
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                        />
                    </div>

                    <div class="py-2">
                        <x-admin.form.label for="password"
                                            class="{{$errors->has('password') ? 'text-red-400' : ''}}">{{ __('Password') }}</x-admin.form.label>

                        <x-admin.form.input id="password" class="{{$errors->has('password') ? 'border-red-400' : ''}}"
                                            type="password"
                                            name="password"
                        />
                    </div>

                    <div class="py-2">
                        <x-admin.form.label for="password_confirmation"
                                            class="block font-medium text-sm text-gray-700{{$errors->has('password') ? 'text-red-400' : ''}}">{{ __('Password Confirmation') }}</x-admin.form.label>

                        <x-admin.form.input id="password_confirmation"
                                            class="{{$errors->has('password') ? 'border-red-400' : ''}}"
                                            type="password"
                                            name="password_confirmation"
                        />
                    </div>

                    <div class="py-2">
                        <h3 class="inline-block text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight py-4 block sm:inline-block flex">
                            Roles</h3>
                        <div class="grid grid-cols-4 gap-4">
                            @forelse ($roles as $role)
                                <div class="col-span-4 sm:col-span-2 md:col-span-1">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        {{ $role->name }}
                                    </label>
                                </div>
                            @empty
                                ----
                            @endforelse
                        </div>
                    </div>

                    <div class="flex justify-end mt-4">
                        <x-admin.form.button>{{ __('Create') }}</x-admin.form.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
