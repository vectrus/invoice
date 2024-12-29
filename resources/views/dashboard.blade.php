<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <div x-data="{
        invoiceStats: {
            thisMonth: {
                total: 0,
                count: 0,
                pending: 0,
                paid: 0
            },
            recentInvoices: [],
            recentEmails: [],
            recentClients: []
        },


        init() {
            this.fetchDashboardData();
        },

        async fetchDashboardData() {
            try {
                const response = await fetch('/dashboard/stats');
                const data = await response.json();

                this.invoiceStats = {
                    thisMonth: data.thisMonth,
                    recentInvoices: data.recentInvoices,
                    recentEmails: data.recentEmails,
                    recentClients: data.recentClients
                };
console.log(this.invoiceStats);
                this.$nextTick(() => {
                    this.initCharts(data.monthlyAmounts)
                });
                console.log('No errors fetching dashboard data:');
            } catch (error) {
                console.error('Error fetching dashboard data:', error);
            }
        },
        initCharts(monthlyData) {
            const ctx = document.getElementById('monthlyInvoices').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthlyData.map(item => {
                        const [year, month] = item.month.split('-');
                        return new Date(year, month - 1).toLocaleString('default', { month: 'short' });
                    }),
                    datasets: [{
                        label: 'Invoice Amount',
                        data: monthlyData.map(item => item.total_amount),
                        backgroundColor: '#3b82f6'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: 'EUR'
                                    }).format(value);
                                }
                            }
                        }
                    }
                }
            });
        },
        formatCurrency(amount) {

            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'EUR'
            }).format(amount)
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString()
        }

    }"
     class="container max-w-7xl mx-auto mt-4 sm:px-6 lg:px-8">

        <!-- Header Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Total Invoiced This Month</h3>
                <p class="text-2xl font-bold text-gray-900" x-text="formatCurrency(invoiceStats.thisMonth.totalAmount)"></p>
                <p class="text-sm text-gray-600">From <span x-text="invoiceStats.thisMonth.count"></span> invoices</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Pending Invoices</h3>
                <p class="text-2xl font-bold text-orange-600" x-text="invoiceStats.thisMonth.pending"></p>
                <p class="text-sm text-gray-600">This month</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Paid Invoices</h3>
                <p class="text-2xl font-bold text-green-600" x-text="invoiceStats.thisMonth.paid"></p>
                <p class="text-sm text-gray-600">This month</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Average Invoice Amount</h3>
                <p class="text-2xl font-bold text-gray-900"
                   x-text="formatCurrency(invoiceStats.thisMonth.total / invoiceStats.thisMonth.count)"></p>
                <p class="text-sm text-gray-600">This month</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium mb-4">Monthly Invoice Amounts</h3>
                <div class="h-64">
                    <canvas id="monthlyInvoices"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium mb-4">Recent Invoices</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        <template x-for="invoice in invoiceStats.recentInvoices" :key="invoice.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a :href="`/invoice/${invoice.id}/edit`"
                                       class="text-blue-600 hover:text-blue-800"
                                       x-text="invoice.invoice_number">
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    x-text="invoice.client.companyname"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    x-text="formatCurrency(invoice.amount_incl)"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            :class="{
                                                'bg-green-100 text-green-800': invoice.status === 'paid',
                                                'bg-yellow-100 text-yellow-800': invoice.status === 'pending',
                                                'bg-red-100 text-red-800': invoice.status === 'overdue'
                                            }"
                                            x-text="invoice.status"
                                        ></span>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium mb-4">Recent Emails</h3>
                <div class="space-y-4">
                    <template x-for="email in invoiceStats.recentEmails" :key="email.id">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <span class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900" x-text="email.subject"></p>
                                <p class="text-sm text-gray-500" x-text="email.client.companyname"></p>
                                <p class="text-xs text-gray-400" x-text="new Date(email.sent_at).toLocaleString()"></p>
                            </div>
                            <div class="flex-shrink-0">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    :class="{
                                        'bg-green-100 text-green-800': email.status === 'delivered',
                                        'bg-yellow-100 text-yellow-800': email.status === 'pending'
                                    }"
                                    x-text="email.status"
                                ></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium mb-4">Recent Clients</h3>
                <div class="space-y-4">
                    <template x-for="client in invoiceStats.recentClients" :key="client.id">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <span class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-building text-green-600"></i>
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900" x-text="client.companyname"></p>
                                <p class="text-sm text-gray-500">
                                    <span x-text="client.total_invoices"></span> invoices,
                                    <span x-text="formatCurrency(client.total_amount)"></span> total
                                </p>
                                <p class="text-xs text-gray-400" x-text="'Added ' + formatDate(client.created_at)"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
