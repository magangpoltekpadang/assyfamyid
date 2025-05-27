@extends('layouts.main')

@section('content')
<div x-data="membershipPackageData()" x-init="fetchPackages()" class="space-y-6 p-6">

  <!-- Header and Add Button -->
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-800">Membership Packages</h1>
    <a href="/membership-packages/create"
       class="px-2 py-0.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
      <i class="fas fa-plus mr-2"></i> Add New Package
    </a>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Total Packages</p>
          <p class="mt-1 text-3xl font-semibold text-gray-900" x-text="totalPackages"></p>
        </div>
        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
          <i class="fas fa-box-open text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Active Packages</p>
          <p class="mt-1 text-3xl font-semibold text-gray-900" x-text="activePackages"></p>
        </div>
        <div class="p-3 rounded-full bg-green-100 text-green-600">
          <i class="fas fa-check-circle text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Inactive Packages</p>
          <p class="mt-1 text-3xl font-semibold text-gray-900" x-text="inactivePackages"></p>
        </div>
        <div class="p-3 rounded-full bg-red-100 text-red-600">
          <i class="fas fa-times-circle text-xl"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Search and Filter -->
  <div class="bg-white rounded-lg shadow p-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label for="search" class="block text-sm font-medium text-gray-700">Search Packages</label>
        <input type="text" id="search" x-model="search" placeholder="Type to search..."
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
               @keydown.enter.prevent="fetchPackages()">
      </div>

      <div>
        <label for="is_active" class="block text-sm font-medium text-gray-700">Active Status</label>
        <select id="is_active" x-model="isActiveFilter"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          <option value="">All</option>
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
      </div>

      <div class="flex items-end space-x-2">
        <button @click="fetchPackages()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
          <i class="fas fa-filter mr-2"></i> Filter
        </button>
        <button @click="resetFilters()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
          <i class="fas fa-times mr-2"></i> Reset
        </button>
      </div>
    </div>
  </div>

  <!-- Packages Table -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration (Days)</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Vehicles</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <template x-for="package in packages" :key="package.package_id">
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="package.package_id"></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="package.package_name"></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="package.duration_days"></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="`$${package.price}`"></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="package.max_vehicles"></td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="package.is_active == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      x-text="package.is_active == 1 ? 'Active' : 'Inactive'"></span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-4">
                <a :href="`/membership-packages/${package.package_id}/edit`" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                  <i class="fas fa-edit"></i>
                </a>
                <button @click="confirmDelete(package.package_id)" class="text-red-600 hover:text-red-900" title="Delete" type="button">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </template>
          <tr x-show="packages.length === 0">
            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No Packages Found</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50" style="display: none;">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirm Delete</h2>
      <p class="text-gray-600 mb-6">Are you sure you want to delete this package?</p>
      <div class="flex justify-end space-x-3">
        <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</button>
        <button @click="deletePackage()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
      </div>
    </div>
  </div>

</div>

@push('scripts')
<script src="{{ asset('JS/mempackages/mempackage-script.js') }}"></script>
@endpush
@endsection
