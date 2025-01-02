<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form
                        action="{{ isset($template) ? route('templates.update', $template) : route('templates.store') }}"
                        method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @if(isset($template))
                            @method('PUT')
                        @endif

                        <div class="mb-6">
                            <label for="logo" class="block text-sm font-medium text-gray-700">Company Logo</label>
                            <input type="file"
                                   name="logo"
                                   id="logo"
                                   accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100">
                            @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if(isset($template) && $template->logo_path)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($template->logo_path) }}"
                                         alt="Current logo"
                                         class="max-w-[200px] rounded-md shadow-sm">
                                </div>
                            @endif
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Template Name</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $template->name ?? '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                          focus:border-indigo-300 focus:ring focus:ring-indigo-200
                                          focus:ring-opacity-50 @error('name') border-red-300 @enderror"
                                   required>
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6 w100">
                            <label class="block text-sm font-medium text-gray-700">HTML Content</label>
                            <div class="mt-1 border rounded-md @error('html') border-red-300 @enderror">

                                <textarea name="html" class="tinyEditor_dep border-0 h-96 w-full" id="content-input"
                                          >
                                    {{ old('html', $template->html ?? '') }}
                                </textarea>
                            </div>
                            @error('html')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6 w100">
                            <label class="block text-sm font-medium text-gray-700">CSS Content</label>
                            <div class="mt-1 border rounded-md @error('content') border-red-300 @enderror">
                                {{--<div id="tinyText" class="h-96 ">{{ old('content', $template->content ?? '') }}</div>--}}
                                <textarea name="content" class="tinyEditor_dep border-0 h-96 w-full" id="input">{{ old('content', $template->content ?? '') }}</textarea>
                            </div>
                            @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="is_default"
                                       value="1"
                                       {{ old('is_default', $template->is_default ?? false) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm
                                              focus:border-indigo-300 focus:ring focus:ring-indigo-200
                                              focus:ring-opacity-50 @error('is_default') border-red-300 @enderror">
                                <span class="ml-2">Set as default template</span>
                            </label>
                            @error('is_default')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($template) ? 'Update Template' : 'Create Template' }}
                                </button>
                                <a href="{{ route('templates.index') }}" class="btn ml-3">Cancel</a>
                            </div>
                            @if(isset($template))
                                <a href="{{ route('templates.preview', $template) }}"
                                   class="btn btn-secondary"
                                   target="_blank">Preview Template</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.23.4/ace.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const editor = ace.edit("editor");
                editor.setTheme("ace/theme/monokai");
                editor.session.setMode("ace/mode/css");
                editor.setShowPrintMargin(false);

                // Update hidden input with editor content before form submission
                document.querySelector('form').addEventListener('submit', function () {
                    document.getElementById('content-input').value = editor.getValue();
                });

                // If there are any validation errors, scroll to the first error
                const firstError = document.querySelector('.text-red-600');
                if (firstError) {
                    firstError.scrollIntoView({behavior: 'smooth', block: 'center'});
                }
            });
        </script>
    @endpush
</x-app-layout>
