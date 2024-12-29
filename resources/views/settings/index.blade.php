<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div x-data="settingsManager({{ Js::from($settings) }})" x-init="init()"
                     class="py-6 px-4 sm:px-6 lg:px-8">


                    <!-- Page Header -->
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate">Application Settings</h2>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <button
                                @click="showCreateModal = true"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Add New Setting
                            </button>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <div
                        x-show="successMessage"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="rounded-md bg-green-50 p-4 mt-4"
                    >
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p x-text="successMessage" class="text-sm font-medium text-green-800"></p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button
                                        @click="successMessage = ''"
                                        class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600"
                                    >
                                        <span class="sr-only">Dismiss</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                             fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Grid -->
                    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <template x-for="(settingsGroup, group) in groupedSettings" :key="group">
                            <div class="bg-white shadow rounded-lg">
                                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                                    <h3 x-text="group"
                                        class="text-lg leading-6 font-medium text-gray-900 capitalize"></h3>
                                </div>
                                <div class="px-4 py-5 sm:p-6">
                                    <template x-for="setting in settingsGroup" :key="setting.id">
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h4 x-text="setting.key"
                                                        class="text-sm font-medium text-gray-900"></h4>
                                                    <div x-text="setting.value"
                                                         class="mt-1 text-sm text-gray-500"></div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <button
                                                        @click="editSetting(setting)"
                                                        class="text-indigo-600 hover:text-indigo-900"
                                                    >
                                                        Edit
                                                    </button>
                                                    <button
                                                        @click="deleteSetting(setting.id)"
                                                        class="text-red-600 hover:text-red-900"
                                                    >
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Create/Edit Modal -->
                    <div
                        x-show="showCreateModal || showEditModal" x-cloak
                        @keydown.escape.window="closeModal()"
                        class="fixed z-10 inset-0 overflow-y-auto"
                        aria-labelledby="modal-title"
                        role="dialog"
                        aria-modal="true"
                    >
                        <div
                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div
                                x-show="showCreateModal || showEditModal"
                                x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="ease-in duration-200"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                @click="closeModal()"
                                aria-hidden="true"
                            ></div>

                            <div
                                class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900"
                                            x-text="modalTitle"></h3>
                                        <div class="mt-4">
                                            <form @submit.prevent="submitForm">
                                                <!-- Key Field -->
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700">Key</label>
                                                    <input
                                                        type="text"
                                                        x-model="formData.key"
                                                        :disabled="showEditModal"
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                    >
                                                    <div
                                                        x-show="errors.key"
                                                        x-text="errors.key"
                                                        class="mt-2 text-sm text-red-600"
                                                    ></div>
                                                </div>

                                                <!-- Value Field -->
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700">Value</label>
                                                    <input
                                                        type="text"
                                                        x-model="formData.value"
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                    >
                                                    <div
                                                        x-show="errors.value"
                                                        x-text="errors.value"
                                                        class="mt-2 text-sm text-red-600"
                                                    ></div>
                                                </div>

                                                <!-- Group Field -->
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700">Group</label>
                                                    <select
                                                        x-model="formData.group"
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                    >
                                                        <option value="company">Company</option>
                                                        <option value="api">API</option>
                                                        <option value="general">General</option>
                                                    </select>
                                                    <div
                                                        x-show="errors.group"
                                                        x-text="errors.group"
                                                        class="mt-2 text-sm text-red-600"
                                                    ></div>
                                                </div>

                                                <!-- Type Field -->
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700">Type</label>
                                                    <select
                                                        x-model="formData.type"
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                    >
                                                        <option value="string">String</option>
                                                        <option value="integer">Integer</option>
                                                        <option value="boolean">Boolean</option>
                                                        <option value="json">JSON</option>
                                                        <option value="array">Array</option>
                                                    </select>
                                                    <div
                                                        x-show="errors.type"
                                                        x-text="errors.type"
                                                        class="mt-2 text-sm text-red-600"
                                                    ></div>
                                                </div>

                                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                                    <button
                                                        type="submit"
                                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                                                    >
                                                        Save
                                                    </button>
                                                    <button
                                                        type="button"
                                                        @click="closeModal()"
                                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                                                    >
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


       {{-- @push('scripts')
            <script>
                function settingsManager(initialSettings) {
                    return {
                        showCreateModal: false,
                        showEditModal: false,
                        modalTitle: '',
                        formData: {
                            key: '',
                            value: '',
                            group: 'general',
                            type: 'string'
                        },
                        groupedSettings: initialSettings || {},  // Initialize with passed data
                        errors: {},

                        init() {
                            // Make sure modals are closed on initialization
                            this.showCreateModal = false;
                            this.showEditModal = false;
                        },

                        createSetting() {
                            this.formData = {
                                key: '',
                                value: '',
                                group: 'general',
                                type: 'string'
                            };
                            this.showCreateModal = true;
                            this.modalTitle = 'Create New Setting';
                            this.errors = {};
                        },

                        editSetting(setting) {
                            this.formData = {
                                id: setting.id,
                                key: setting.key,
                                value: setting.value,
                                group: setting.group,
                                type: setting.type
                            };
                            this.showEditModal = true;
                            this.modalTitle = 'Edit Setting';
                            this.errors = {};
                        },

                        async deleteSetting(id) {
                            if (!confirm('Are you sure you want to delete this setting?')) {
                                return;
                            }

                            try {
                                const response = await fetch(`/settings/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    }
                                });

                                const data = await response.json();

                                if (data.success) {
                                    // Show success message
                                    this.$dispatch('notify', {
                                        type: 'success',
                                        message: 'Setting deleted successfully'
                                    });

                                    // Refresh the page
                                    window.location.reload();
                                } else {
                                    this.$dispatch('notify', {
                                        type: 'error',
                                        message: data.message || 'Error deleting setting'
                                    });
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                this.$dispatch('notify', {
                                    type: 'error',
                                    message: 'An error occurred while deleting the setting'
                                });
                            }
                        },

                        async submitForm() {
                            this.errors = {};

                            const url = this.showEditModal
                                ? `/settings/${this.formData.id}`
                                : '/settings';

                            const method = this.showEditModal ? 'PUT' : 'POST';

                            try {
                                const response = await fetch(url, {
                                    method: method,
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify(this.formData)
                                });

                                const data = await response.json();

                                if (data.success) {
                                    // Show success message
                                    this.$dispatch('notify', {
                                        type: 'success',
                                        message: this.showEditModal ? 'Setting updated successfully' : 'Setting created successfully'
                                    });

                                    // Close modal and refresh page
                                    this.closeModal();
                                    window.location.reload();
                                } else {
                                    if (data.errors) {
                                        this.errors = data.errors;
                                    } else {
                                        this.$dispatch('notify', {
                                            type: 'error',
                                            message: data.message || 'Error saving setting'
                                        });
                                    }
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                this.$dispatch('notify', {
                                    type: 'error',
                                    message: 'An error occurred while saving the setting'
                                });
                            }
                        },

                        validateForm() {
                            this.errors = {};
                            let isValid = true;

                            if (!this.formData.key?.trim()) {
                                this.errors.key = 'Key is required';
                                isValid = false;
                            }

                            if (!this.formData.value?.toString().trim()) {
                                this.errors.value = 'Value is required';
                                isValid = false;
                            }

                            // Validate value based on type
                            switch (this.formData.type) {
                                case 'integer':
                                    if (isNaN(parseInt(this.formData.value))) {
                                        this.errors.value = 'Value must be a number';
                                        isValid = false;
                                    }
                                    break;
                                case 'boolean':
                                    if (![true, false, 'true', 'false', 0, 1, '0', '1'].includes(this.formData.value)) {
                                        this.errors.value = 'Value must be true or false';
                                        isValid = false;
                                    }
                                    break;
                                case 'json':
                                    try {
                                        JSON.parse(this.formData.value);
                                    } catch (e) {
                                        this.errors.value = 'Value must be valid JSON';
                                        isValid = false;
                                    }
                                    break;
                            }

                            return isValid;
                        },

                        closeModal() {
                            this.showCreateModal = false;
                            this.showEditModal = false;
                            this.formData = {
                                key: '',
                                value: '',
                                group: 'general',
                                type: 'string'
                            };
                            this.errors = {};
                        },

                        // Helper method to format the display value based on type
                        formatValue(value, type) {
                            switch (type) {
                                case 'json':
                                case 'array':
                                    try {
                                        return JSON.stringify(typeof value === 'string' ? JSON.parse(value) : value);
                                    } catch {
                                        return value;
                                    }
                                case 'boolean':
                                    return value ? 'True' : 'False';
                                default:
                                    return value;
                            }
                        }
                    };
                }
            </script>
    @endpush--}}
</x-app-layout>
