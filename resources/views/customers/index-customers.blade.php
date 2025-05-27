@extends('layouts.main')

@section('content')
<div x-data="customerData()" x-init="fetchCustomers()" class="space-y-6 p-6">

  <!-- Header and Add Button -->
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-800">Customer List</h1>
    <a href="/customers/create"
       class="px-2 py-0.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
      <i class="fas fa-plus mr-2"></i> Add New Customer
    </a>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Total Customers</p>
          <p class="mt-1 text-3xl font-semibold text-gray-900" x-text="totalCustomers"></p>
        </div>
        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
          <i class="fas fa-users text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Member Customers</p>
          <p class="mt-1 text-3xl font-semibold text-gray-900" x-text="memberCustomers"></p>
        </div>
        <div class="p-3 rounded-full bg-green-100 text-green-600">
          <i class="fas fa-id-badge text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Non-Member Customers</p>
          <p class="mt-1 text-3xl font-semibold text-gray-900" x-text="nonMemberCustomers"></p>
        </div>
        <div class="p-3 rounded-full bg-red-100 text-red-600">
          <i class="fas fa-user-slash text-xl"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Search and Filter -->
  <div class="bg-white rounded-lg shadow p-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label for="search" class="block text-sm font-medium text-gray-700">Search Customers</label>
        <input type="text" id="search" x-model="search" placeholder="Type to search..."
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
               @keydown.enter.prevent="fetchCustomers()">
      </div>

      <div>
        <label for="is_member" class="block text-sm font-medium text-gray-700">Member Status</label>
        <select id="is_member" x-model="isMemberFilter"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          <option value="">All</option>
          <option value="1">Member</option>
          <option value="0">Non-Member</option>
        </select>
      </div>

      <div class="flex items-end space-x-2">
        <button @click="fetchCustomers()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
          <i class="fas fa-filter mr-2"></i> Filter
        </button>
        <button @click="resetFilters()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
          <i class="fas fa-times mr-2"></i> Reset
        </button>
      </div>
    </div>
  </div>

  <!-- Customers Table -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plate Number</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <template x-for="customer in customers" :key="customer.customer_id">
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="customer.customer_id"></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="customer.name"></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="customer.plate_number"></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="customer.phone_number"></td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="customer.is_member ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      x-text="customer.is_member ? 'Active' : 'Inactive'"></span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-4">
                <a :href="`/customers/${customer.customer_id}`" class="text-blue-600 hover:text-blue-900" title="View">
                    <i class="fas fa-info-circle"></i>
                </a>
                <a :href="`/customers/${customer.customer_id}/edit`" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                <button @click="confirmDelete(customer.customer_id)" class="text-red-600 hover:text-red-900" title="Delete" type="button">
                    <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </template>
          <tr x-show="customers.length === 0">
            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No Customers Found</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50" style="display: none;">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirm Delete</h2>
      <p class="text-gray-600 mb-6">Are you sure you want to delete this customer?</p>
      <div class="flex justify-end space-x-3">
        <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</button>
        <button @click="deleteCustomer()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="{{ asset('JS/customers/customer-script.js') }}"></script>
@endpush
@endsection
