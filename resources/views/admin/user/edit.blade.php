<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <x-admin.breadcrumb href="{{route('user.index')}}"
                                            title="{{ __('Update user') }}">{{ __('<< Back to all users') }}</x-admin.breadcrumb>
                        <errors/>
                    </div>
                    <div class="grid grid-cols-4 w-full py-2 bg-white overflow-hidden">

                        <form method="POST" action="{{ route('user.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="py-2 col-span-4">

                                <x-admin.form.label for="name" class="{{$errors->has('name') ? 'text-red-400' : ''}}">{{ __('Name') }}</x-admin.form.label>

                                <input id="name" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 {{$errors->has('name') ? 'border-red-400' : ''}}"
                                       type="text"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                />
                            </div>

                            <div class="py-2 col-span-1">
                                <x-admin.form.label for="email" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 {{$errors->has('email') ? 'text-red-400' : ''}}">{{ __('Email') }}</x-admin.form.label>

                                <input id="email" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 {{$errors->has('email') ? 'border-red-400' : ''}}"
                                       type="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                />
                            </div>

                            <div class="py-2">
                                <x-admin.form.label for="password"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 {{$errors->has('password') ? 'text-red-400' : ''}}">{{ __('Password') }}</x-admin.form.label>

                                <input id="password" class="{{$errors->has('password') ? 'border-red-400' : ''}}"
                                       type="password"
                                       name="password"
                                />
                            </div>

                            <div class="py-2">
                                <x-admin.form.label for="password_confirmation"
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 {{$errors->has('password') ? 'text-red-400' : ''}}">{{ __('Password Confirmation') }}</x-admin.form.label>

                                <input id="password_confirmation" class="{{$errors->has('password') ? 'border-red-400' : ''}}"
                                       type="password"
                                       name="password_confirmation"
                                />
                            </div>

                            <div class="py-2 col-span-4">
                                <h3 class="inline-block text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight py-4 block sm:inline-block flex">
                                    Roles</h3>
                                <div class="grid grid-cols-4 w-full gap-4">
                                    @forelse ($roles as $role)
                                        <div class="col-span-1 sm:col-span-2 md:col-span-1">
                                            <x-admin.form.label class="form-check-label">
                                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                                       {{ in_array($role->id, $userHasRoles) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                {{ $role->name }}
                                            </x-admin.form.label>
                                            <br>
                                        </div>
                                    @empty
                                        ----
                                    @endforelse
                                </div>
                            </div>




                            <div class="flex justify-end mt-4">
                                <x-admin.form.button>{{ __('Update') }}</x-admin.form.button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
