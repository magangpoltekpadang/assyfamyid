@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-2xl">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Edit Outlet</h2>

    <form method="POST" action="{{ url('outlets/' . $outlet->outlet_id) }}">
        @csrf
        @method('PUT')

        {{-- Outlet Name --}}
        <div class="mb-4">
            <label for="outlet_name" class="block text-sm font-medium text-gray-700">Outlet Name</label>
            <input type="text" id="outlet_name" name="outlet_name"
                value="{{ old('outlet_name', $outlet->outlet_name) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                required>
        </div>

        {{-- Address --}}
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
            <input type="text" id="address" name="address"
                value="{{ old('address', $outlet->address) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
        </div>

        {{-- Phone Number --}}
        <div class="mb-4">
            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number"
                value="{{ old('phone_number', $outlet->phone_number) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
        </div>

        {{-- Active Status --}}
        <div class="mb-6">
            <label for="is_active" class="block text-sm font-medium text-gray-700">Active Status</label>
            <select id="is_active" name="is_active"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
                <option value="1" {{ old('is_active', $outlet->is_active) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('is_active', $outlet->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
