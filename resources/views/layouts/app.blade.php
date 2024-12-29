<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Vectrus Invoice</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet"/>
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
                    showSuccessMessage(message) {
                        this.successMessage = message;
                        setTimeout(() => {
                            this.successMessage = '';
                        }, 3000); // Message will disappear after 3 seconds
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

        {{--<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">--}}


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
        <x-head.tinymce-config/>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
