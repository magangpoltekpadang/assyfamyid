@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-2xl">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Edit Customer</h2>

    <form method="POST" action="{{ url('customers/' . $customer->customer_id) }}">
        @csrf
        @method('PUT')

        {{-- Plate Number --}}
        <div class="mb-4">
            <label for="plate_number" class="block text-sm font-medium text-gray-700">Plate Number</label>
            <input type="text" id="plate_number" name="plate_number"
                value="{{ old('plate_number', $customer->plate_number) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                maxlength="15">
        </div>

        {{-- Name --}}
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name"
                value="{{ old('name', $customer->name) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                maxlength="100" required>
        </div>

        {{-- Phone Number --}}
        <div class="mb-4">
            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number"
                value="{{ old('phone_number', $customer->phone_number) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                maxlength="20">
        </div>

        {{-- Vehicle Type --}}
        <div class="mb-4">
            <label for="vehicle_type_id" class="block text-sm font-medium text-gray-700">Vehicle Type</label>
            <select id="vehicle_type_id" name="vehicle_type_id"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
                <option value="">-- Select Vehicle Type --</option>
                @foreach ($vehicleTypes as $type)
                    <option value="{{ $type->vehicle_type_id }}" {{ $customer->vehicle_type_id == $type->vehicle_type_id ? 'selected' : '' }}>
                        {{ $type->type_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Vehicle Color --}}
        <div class="mb-4">
            <label for="vehicle_color" class="block text-sm font-medium text-gray-700">Vehicle Color</label>
            <input type="text" id="vehicle_color" name="vehicle_color"
                value="{{ old('vehicle_color', $customer->vehicle_color) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                maxlength="30">
        </div>

        {{-- Member Number --}}
        <div class="mb-4">
            <label for="member_number" class="block text-sm font-medium text-gray-700">Member Number</label>
            <input type="text" id="member_number" name="member_number"
                value="{{ old('member_number', $customer->member_number) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                maxlength="20">
        </div>

        {{-- Join Date --}}
        <div class="mb-4">
            <label for="join_date" class="block text-sm font-medium text-gray-700">Join Date</label>
            <input type="date" id="join_date" name="join_date"
                value="{{ old('join_date', $customer->join_date) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
        </div>

        {{-- Member Expiry Date --}}
        <div class="mb-4">
            <label for="member_expiry_date" class="block text-sm font-medium text-gray-700">Member Expiry Date</label>
            <input type="datetime-local" id="member_expiry_date" name="member_expiry_date"
                value="{{ old('member_expiry_date', $customer->member_expiry_date ? \Carbon\Carbon::parse($customer->member_expiry_date)->format('Y-m-d\TH:i') : '') }}"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
        </div>

        {{-- Is Member --}}
        <div class="mb-6">
            <label for="is_member" class="block text-sm font-medium text-gray-700">Is Member?</label>
            <select id="is_member" name="is_member"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
                <option value="1" {{ $customer->is_member ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$customer->is_member ? 'selected' : '' }}>No</option>
            </select>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
