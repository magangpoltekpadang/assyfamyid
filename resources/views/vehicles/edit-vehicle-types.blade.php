@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-2xl">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Edit Vehicle Type</h2>

    <form method="POST" action="{{ url('vehicle-types/' . $vehicleType->vehicle_type_id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="type_name" class="block text-sm font-medium text-gray-700">Type Name</label>
            <input type="text" id="type_name" name="type_name" value="{{ old('type_name', $vehicleType->type_name) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                required>
        </div>

        <div class="mb-4">
            <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
            <input type="text" id="code" name="code" value="{{ old('code', $vehicleType->code) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" rows="3"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2 resize-none">{{ old('description', $vehicleType->description) }}</textarea>
        </div>

        <div class="mb-6">
            <label for="is_active" class="block text-sm font-medium text-gray-700">Active Status</label>
            <select id="is_active" name="is_active"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
                <option value="1" {{ $vehicleType->is_active == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $vehicleType->is_active == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
