<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold">Invoice Templates</h2>
                <a href="{{ route('templates.create') }}" class="btn-primary">
                    Create New Template
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left">Name</th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left">Default</th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white">
                        @foreach($templates as $template)
                            <tr>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    {{ $template->name }}
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    @if($template->is_default)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Default
                                            </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('templates.edit', $template) }}"
                                           class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <a href="{{ route('templates.preview', $template) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           target="_blank">Preview</a>
                                        @unless($template->is_default)
                                            <form action="{{ route('templates.destroy', $template) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this template?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Delete
                                                </button>
                                            </form>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
