@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-2xl">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Add Service</h2>

    <form method="POST" action="/services">
        @csrf

        {{-- Service Name --}}
        <div class="mb-4">
            <label for="service_name" class="block text-sm font-medium text-gray-700">Service Name</label>
            <input type="text" id="service_name" name="service_name"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                required>
        </div>

        {{-- Price --}}
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number" id="price" name="price" step="0.01"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                required>
        </div>

        {{-- Estimated Duration (in minutes) --}}
        <div class="mb-4">
            <label for="estimated_duration" class="block text-sm font-medium text-gray-700">Estimated Duration (minutes)</label>
            <input type="number" id="estimated_duration" name="estimated_duration"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                required>
        </div>

        {{-- Service Type --}}
        <div class="mb-4">
            <label for="service_type_id" class="block text-sm font-medium text-gray-700">Service Type</label>
            <select id="service_type_id" name="service_type_id"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                required>
                @foreach ($serviceTypes as $type)
                    <option value="{{ $type->service_type_id }}">{{ $type->type_name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Outlet --}}
        <div class="mb-4">
            <label for="outlet_id" class="block text-sm font-medium text-gray-700">Outlet</label>
            <select id="outlet_id" name="outlet_id"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                required>
                @foreach ($outlets as $outlet)
                    <option value="{{ $outlet->outlet_id }}">{{ $outlet->outlet_name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Active Status --}}
        <div class="mb-6">
            <label for="is_active" class="block text-sm font-medium text-gray-700">Active Status</label>
            <select id="is_active" name="is_active"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
                <option value="1">Active</option>
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
