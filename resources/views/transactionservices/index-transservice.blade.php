@extends('layouts.main')

@section('content')
<div x-data="transactionServiceData()" x-init="init()" class="space-y-6">

    <!-- Header and Create Button -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Service Transactions</h1>
        <a href="/transaction-services/create"
           class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Add New Transaction
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-file-invoice-dollar text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Transactions</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900" x-text="stats.total_transactions"></p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-coins text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900" x-text="formatCurrency(stats.total_revenue)"></p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-calendar-day text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Today's Transactions</p>
                <p class="mt-1 text-3xl font-semibold text-gray-900" x-text="stats.today_transactions"></p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Field -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" @change="fetchTransactions()" @input="fetchTransactions()"
                    id="search"
                    x-model="search"
                    placeholder="Type to search..."
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status"
                        x-model="status"
                        @change="fetchTransactions()"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="canceled">Canceled</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class ="flex items-end">
                <button @click="fetchTransactions()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <button @click="resetFilters()" class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    <i class="fas fa-times mr-2"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Code</th>
                    <th class="px-6 py-3 text-left">Customer</th>
                    <th class="px-6 py-3 text-left">Service</th>
                    <th class="px-6 py-3 text-left">Qty</th>
                    <th class="px-6 py-3 text-left">Unit Price</th>
                    <th class="px-6 py-3 text-left">Total</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Staff</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                <template x-for="transaction in transactions" :key="transaction.transaction_service_id">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="transaction.transaction_code"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="transaction.customer_name"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="transaction.service_name"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="transaction.quantity"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="formatCurrency(transaction.unit_price)"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="formatCurrency(transaction.total_price)"></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="{
                                    'bg-yellow-100 text-yellow-800': transaction.status === 'pending',
                                    'bg-blue-100 text-blue-800': transaction.status === 'in_progress',
                                    'bg-green-100 text-green-800': transaction.status === 'completed',
                                    'bg-red-100 text-red-800': transaction.status === 'canceled'
                                }"
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                x-text="transaction.status.replace('_', ' ').toUpperCase()"></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="transaction.staff_name"></td>
                        <td class="px-6 py-4 whitespace-nowrap flex space-x-3">
                            <a :href="`/transaction-services/${transaction.transaction_service_id}`"
                               class="text-blue-600 hover:text-blue-900" title="View">
                                <i class="fas fa-info-circle"></i>
                            </a>
                            <a :href="`/transaction-services/${transaction.transaction_service_id}/edit`"
                               class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button @click="confirmDelete(transaction.transaction_service_id)"
                                    class="text-red-600 hover:text-red-900" title="Delete" type="button">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </template>
                <tr x-show="transactions.length === 0">
                    <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">No Transactions Found</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6" x-show="pagination.last_page > 1">
        <div class="flex-1 flex justify-between sm:hidden">
            <button @click="previousPage()" :disabled="pagination.current_page === 1"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Previous
            </button>
            <button @click="nextPage()" :disabled="pagination.current_page === pagination.last_page"
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Next
            </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span x-text="pagination.from"></span>
                    to
                    <span x-text="pagination.to"></span>
                    of
                    <span x-text="pagination.total"></span>
                    results
                </p>
            </div>
            <div>
                <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <button @click="goToPage(1)" :disabled="pagination.current_page === 1"
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">First</span>
                        <i class="fas fa-angle-double-left"></i>
                    </button>
                    <button @click="previousPage()" :disabled="pagination.current_page === 1"
                            class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <i class="fas fa-angle-left"></i>
                    </button>

                    <template x-for="page in pagination.last_page" :key="page">
                        <button @click="goToPage(page)"
                                :class="pagination.current_page === page ? 'z-10 bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium">
                            <span x-text="page"></span>
                        </button>
                    </template>

                    <button @click="nextPage()" :disabled="pagination.current_page === pagination.last_page"
                            class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <i class="fas fa-angle-right"></i>
                    </button>
                    <button @click="goToPage(pagination.last_page)" :disabled="pagination.current_page === pagination.last_page"
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Last</span>
                        <i class="fas fa-angle-double-right"></i>
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showDeleteModal = false" class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirm Delete</h2>
            <p class="mb-6 text-gray-600">Are you sure you want to delete this transaction service?</p>
            <div class="flex justify-end space-x-3">
                <button @click="showDeleteModal = false"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                <button @click="deleteTransactionService()"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
            </div>
        </div>
    </div>

</div>

<!-- JS Path fixed -->
<script src="{{ asset('JS/transactionservices/transaction-service-script.js') }}"></script>
@endsection
