@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-2xl">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Add Membership Package</h2>

    <form method="POST" action="/membership-packages">
        @csrf

        {{-- Package Name --}}
        <div class="mb-4">
            <label for="package_name" class="block text-sm font-medium text-gray-700">Package Name</label>
            <input type="text" id="package_name" name="package_name"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                required>
        </div>

        {{-- Price --}}
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number" id="price" name="price" step="0.01" min="0"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                required>
        </div>

        {{-- Duration (in days) --}}
        <div class="mb-4">
            <label for="duration_days" class="block text-sm font-medium text-gray-700">Duration (Days)</label>
            <input type="number" id="duration_days" name="duration_days" min="1"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                required>
        </div>

        {{-- Max Vehicles --}}
        <div class="mb-4">
            <label for="max_vehicle" class="block text-sm font-medium text-gray-700">Max Vehicles</label>
            <input type="number" id="max_vehicle" name="max_vehicle" min="1"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                required>
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" rows="3"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2 resize-none"></textarea>
        </div>

        {{-- Active Status --}}
        <div class="mb-6">
            <label for="is_active" class="block text-sm font-medium text-gray-700">Active Status</label>
            <select id="is_active" name="is_active"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
                <option value="1" selected>Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Save
            </button>
        </div>
    </form>
</div>
@endsection
