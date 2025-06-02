@extends('layouts.main')

@section('content')
<div x-data="notificationStatusData()" x-init="init()" class="space-y-6 p-6">

  <!-- Header and Add Button -->
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-800">Notification Statuses</h1>
    <a href="/notification-statuses/create"
       class="px-2 py-0.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
      <i class="fas fa-plus mr-2"></i> Add New Notification Status
    </a>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Total Notification Statuses -->
    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Total Notification Statuses</p>
          <p class="mt-1 text-3xl font-semibold text-gray-900" x-text="pagination.total"></p>
        </div>
        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
          <i class="fas fa-bell text-xl"></i>
        </div>
      </div>
    </div>

    <!-- NOTE: Karena `is_active` tidak ada, cards berikut dihilangkan atau bisa diubah jika ada info lain -->
    {{--
    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Active Statuses</p>
          <p class="mt-1 text-3xl font-semibold text-gray-900"
             x-text="notificationStatuses.filter(s => s.is_active).length"></p>
        </div>
        <div class="p-3 rounded-full bg-green-100 text-green-600">
          <i class="fas fa-check-circle text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Inactive Statuses</p>
          <p class="mt-1 text-3xl font-semibold text-gray-900"
             x-text="notificationStatuses.filter(s => !s.is_active).length"></p>
        </div>
        <div class="p-3 rounded-full bg-red-100 text-red-600">
          <i class="fas fa-times-circle text-xl"></i>
        </div>
      </div>
    </div>
    --}}
  </div>

  <!-- Search and Filter -->
<div class="bg-white rounded-lg shadow p-4">
  <div class="flex items-end justify-between space-x-4">
    <!-- Search -->
    <div class="flex-1 max-w-md">
      <label for="search" class="block text-sm font-medium text-gray-700">Search Notification Status</label>
      <input type="text" id="search" x-model.debounce.500ms="search" placeholder="Type to search..."
             class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
             @keydown.enter.prevent="fetchNotificationStatuses(1)">
    </div>

    <!-- Buttons -->
    <div class="flex space-x-2">
      <button @click="fetchNotificationStatuses(1)" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
        <i class="fas fa-filter mr-2"></i> Filter
      </button>
      <button @click="resetFilters()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 flex items-center">
        <i class="fas fa-times mr-2"></i> Reset
      </button>
    </div>
  </div>
</div>


  <!-- Notification Status Table -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <template x-for="status in notificationStatuses" :key="status.status_id">
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="status.status_id"></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="status.status_name"></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="status.code"></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="status.description || '-'"></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-4">
                <a :href="`/notification-statuses/${status.status_id}`" class="text-blue-600 hover:text-blue-900" title="View">
                  <i class="fas fa-info-circle"></i>
                </a>
                <a :href="`/notification-statuses/${status.status_id}/edit`" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                  <i class="fas fa-edit"></i>
                </a>
                <button @click="confirmDelete(status.status_id)" class="text-red-600 hover:text-red-900" title="Delete">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </template>
          <tr x-show="notificationStatuses.length === 0">
            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No Notification Statuses Found</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50" style="display: none;">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirm Delete</h2>
      <p class="text-gray-600 mb-6">Are you sure you want to delete this notification status?</p>
      <div class="flex justify-end space-x-3">
        <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</button>
        <button @click="deleteNotificationStatus()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
      </div>
    </div>
  </div>

</div>

@push('scripts')
<script src="{{ asset('JS/notifstatuses/notification-status-script.js') }}"></script>
@endpush
@endsection
