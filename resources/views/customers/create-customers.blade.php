@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-2xl">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Add Customer</h2>

    <form method="POST" action="/customers">
        @csrf

        {{-- Plate Number --}}
        <div class="mb-4">
            <label for="plate_number" class="block text-sm font-medium text-gray-700">Plate Number</label>
            <input type="text" id="plate_number" name="plate_number"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                maxlength="15" placeholder="B 1234 XYZ">
        </div>

        {{-- Name --}}
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                maxlength="100" required>
        </div>

        {{-- Phone Number --}}
        <div class="mb-4">
            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                maxlength="20" placeholder="08123456789">
        </div>

        {{-- Vehicle Type --}}
        <div class="mb-4">
            <label for="vehicle_type_id" class="block text-sm font-medium text-gray-700">Vehicle Type</label>
            <select id="vehicle_type_id" name="vehicle_type_id"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
                <option value="">-- Select Vehicle Type --</option>
                @foreach ($vehicleTypes as $type)
                    <option value="{{ $type->vehicle_type_id }}">{{ $type->type_name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Vehicle Color --}}
        <div class="mb-4">
            <label for="vehicle_color" class="block text-sm font-medium text-gray-700">Vehicle Color</label>
            <input type="text" id="vehicle_color" name="vehicle_color"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                maxlength="30" placeholder="Merah">
        </div>

        {{-- Member Number --}}
        <div class="mb-4">
            <label for="member_number" class="block text-sm font-medium text-gray-700">Member Number</label>
            <input type="text" id="member_number" name="member_number"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                maxlength="20" placeholder="Optional if member">
        </div>

        {{-- Join Date --}}
        <div class="mb-4">
            <label for="join_date" class="block text-sm font-medium text-gray-700">Join Date</label>
            <input type="date" id="join_date" name="join_date"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
        </div>

        {{-- Member Expiry Date --}}
        <div class="mb-4">
            <label for="member_expiry_date" class="block text-sm font-medium text-gray-700">Member Expiry Date</label>
            <input type="datetime-local" id="member_expiry_date" name="member_expiry_date"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
        </div>

        {{-- Is Member --}}
        <div class="mb-6">
            <label for="is_member" class="block text-sm font-medium text-gray-700">Is Member</label>
            <select id="is_member" name="is_member"
                class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
                <option value="">-- Select Status --</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Submit
            </button>
        </div>
    </form>
</div>
@endsection
