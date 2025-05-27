@extends('layouts.main')

@section('content')
<div x-data="vehicleTypeData()" x-init="init()" class="space-y-6">

    <!-- Header and Create Button -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Vehicle Types</h1>
        <a href="/vehicle-types/create"
           {{-- @click.prevent="alert('Create functionality would go here')" --}}
           class="px-2 py-0.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Add New Type
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Total Vehicle Types -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Vehicle Types</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900" x-text="pagination.total"></p>
                </div>
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-car text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Types -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Types</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900"
                       x-text="vehicleTypes.filter(v => v.is_active).length">
                    </p>
                </div>
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Inactive Types -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Inactive Types</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900"
                    x-text="vehicleTypes.filter(v => !v.is_active).length">
                    </p>
                </div>
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Field -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" @change="fetchVehicleTypes()" @input="fetchVehicleTypes()"
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
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class ="flex items-end">
                <button @click="fetchVehicleTypes()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <button @click="resetFilters()" class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    <i class="fas fa-times mr-2"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Vehicle Types Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="vehicleType in vehicleTypes" :key="vehicleType.vehicle_type_id">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="vehicleType.code"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="vehicleType.type_name"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="vehicleType.description"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span x-bind:class="vehicleType.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    x-text="vehicleType.is_active ? 'Active' : 'Inactive'">
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-4">
                                <a :href="`/vehicle-types/${vehicleType.vehicle_type_id}`"
                                class="text-blue-600 hover:text-blue-900" title="View">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                <a :href="`/vehicle-types/${vehicleType.vehicle_type_id}/edit`"
                                class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button @click="confirmDelete(vehicleType.vehicle_type_id)"
                                        class="text-red-600 hover:text-red-900" title="Delete" type="button">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="vehicleTypes.length === 0">
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No Vehicle Types Found</td>
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
                    <p class= "text-sm text-gray-700">
                        Showing <span class="font-medium" x-text="pagination.from"></span> to
                        <span class="font-medium" x-text="pagination.to"></span> of
                        <span class="font-medium" x-text="pagination.total"></span>
                        Results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button @click="changePage(1)" :disabled="pagination.current_page === 1"
                                class="relative inline-flex items-center px-2 py-2 rounded-1-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Previous</span>
                            <i class="fas fa-angle-left"></i>
                        </button>

                        <template x-for="page in pagination.links" :key="page.label">
                            <button @click="changePage(page.label)"
                                    :disabled="!Number.isInteger(Number(page.label)) || page.active"
                                    :class="page.active ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                    x-text="page.label">
                            </button>
                        </template>


                        <button @click="nextPage()" :disabled="pagination.current_page === pagination.last_page"
                                class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Next</span>
                            <i class="fas fa-angle-right"></i>
                        </button>
                        <button @click="changePage(pagination.last_page)" :disabled="pagination.current_page === pagination.last_page"
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Last</span>
                            <i class="fas fa-angle-double-right"></i>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirm Delete</h2>
            <p class="text-gray-600 mb-6">Are you sure you want to delete this vehicle type?</p>
            <div class="flex justify-end space-x-3">
                <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</button>
                <button @click="deleteVehicleType()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('JS/vehicles/vehicle-type-script.js') }}"></script>
@endpush
@endsection
