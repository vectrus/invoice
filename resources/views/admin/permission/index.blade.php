<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Permissions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @hasrole('admin')
                        <x-admin.add-link href="{{ route('permission.create') }}">
                            {{ __('Add Permission') }}
                        </x-admin.add-link>
                    @endhasrole

                    <div class="py-2">
                        <div class="min-w-full border-b border-gray-200 shadow overflow-x-auto">
                            <x-admin.grid.search action="{{ route('permission.index') }}"/>
                            <x-admin.grid.table>
                                <x-slot name="head">
                                    <tr>
                                        <x-admin.grid.th>
                                            @include('admin.includes.sort-link', ['label' => 'Name', 'attribute' => 'name'])
                                        </x-admin.grid.th>
                                        @hasrole('admin')
                                            <x-admin.grid.th>
                                                {{ __('Actions') }}
                                            </x-admin.grid.th>
                                        @endhasrole
                                    </tr>
                                </x-slot>
                                <x-slot name="body">
                                    @foreach($permissions as $permission)
                                        <tr>
                                            <x-admin.grid.td>
                                                <div class="text-sm text-gray-900">
                                                    <a href="{{route('permission.show', $permission->id)}}"
                                                       class="no-underline hover:underline text-cyan-600">{{ $permission->name }}</a>
                                                </div>
                                            </x-admin.grid.td>
                                            @hasrole('admin')
                                                <x-admin.grid.td>
                                                    <form action="{{ route('permission.destroy', $permission->id) }}" method="POST">
                                                        <div class="flex">
                                                            @hasrole('admin')
                                                                <a href="{{route('permission.edit', $permission->id)}}"
                                                                   class="inline-flex items-center px-4 py-2 mr-4 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                                    {{ __('Edit') }}
                                                                </a>
                                                            @endhasrole

                                                            @hasrole('admin')
                                                                @csrf
                                                                @method('DELETE')
                                                                <button
                                                                    class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150"
                                                                    onclick="return confirm('{{ __('Are you sure you want to delete?') }}')">
                                                                    {{ __('Delete') }}
                                                                </button>
                                                            @endhasrole
                                                        </div>
                                                    </form>
                                                </x-admin.grid.td>
                                            @endhasrole
                                        </tr>
                                    @endforeach
                                    @if($permissions->isEmpty())
                                        <tr>
                                            <td colspan="2">
                                                <div class="flex flex-col justify-center items-center py-4 text-lg">
                                                    {{ __('No Result Found') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </x-slot>
                            </x-admin.grid.table>
                        </div>
                        <div class="py-8">
                            {{ $permissions->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
