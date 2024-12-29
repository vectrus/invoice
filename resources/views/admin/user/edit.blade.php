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
                    <div class="w-full py-2 bg-white overflow-hidden">

                        <form method="POST" action="{{ route('user.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="py-2">
                                <label for="name" class="{{$errors->has('name') ? 'text-red-400' : ''}}">{{ __('Name') }}</label>

                                <input id="name" class="{{$errors->has('name') ? 'border-red-400' : ''}}"
                                       type="text"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                />
                            </div>

                            <div class="py-2">
                                <label for="email" class="{{$errors->has('email') ? 'text-red-400' : ''}}">{{ __('Email') }}</label>

                                <input id="email" class="{{$errors->has('email') ? 'border-red-400' : ''}}"
                                       type="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                />
                            </div>

                            <div class="py-2">
                                <label for="password"
                                       class="{{$errors->has('password') ? 'text-red-400' : ''}}">{{ __('Password') }}</label>

                                <input id="password" class="{{$errors->has('password') ? 'border-red-400' : ''}}"
                                       type="password"
                                       name="password"
                                />
                            </div>

                            <div class="py-2">
                                <label for="password_confirmation"
                                       class="{{$errors->has('password') ? 'text-red-400' : ''}}">{{ __('Password Confirmation') }}</label>

                                <input id="password_confirmation" class="{{$errors->has('password') ? 'border-red-400' : ''}}"
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
                                                       {{ in_array($role->id, $userHasRoles) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                {{ $role->name }}
                                            </ >
                                        </div>
                                    @empty
                                        ----
                                    @endforelse
                                </div>
                            </div>


                            {{--<div class="py-2">
                                <label for="company_id"
                                                    class="{{$errors->has('email') ? 'text-red-400' : ''}}">{{ __('Bedrijf') }}</label>

                                <select id="company_id" class="{{$errors->has('company_id') ? 'border-red-400' : ''}}"
                                                    name="company_id"
                                                    value="{{ old('email', $user->company_id) }}"
                                />
                            </div>--}}
                            <div class="py-2">
                                <label for="client_id"
                                       class="{{$errors->has('client_id') ? 'text-red-400' : ''}}">{{ __('Huurder') }}</label>

                                <select id="client_id" class="{{$errors->has('client_id') ? 'border-red-400' : ''}}" name="client_id">
                                    <option value="{{ old('client_id', $user->client_id) }}">{{ $user->name}}</option>
                                    @foreach($clients as $client)
                                        <option
                                            value="{{ old('client_id', $client->client_id) }}">{{ $client->achternaam}}  {{ $client->voorletters}}
                                            ({{ $client->roepnaam}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="py-2">
                                <label for="client_id"
                                       class="{{$errors->has('lessor_id') ? 'text-red-400' : ''}}">{{ __('Verhuurder') }}</label>

                                <select id="lessor_id" class="{{$errors->has('lessor_id') ? 'border-red-400' : ''}}" name="client_id">
                                    <option value="{{ old('lessor_id', $user->lessor_id) }}">{{ $user->naam}} {{ $user->name}}</option>
                                    @foreach($lessors as $lessor)
                                        <option
                                            value="{{ old('client_id', $client->client_id) }}">{{ $lessor->achternaam}}  {{ $lessor->voorletters}}
                                            ({{ $lessor->roepnaam}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="flex justify-end mt-4">
                                <button>{{ __('Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
