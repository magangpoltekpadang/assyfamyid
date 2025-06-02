@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto mt-12 bg-white rounded-2xl shadow-md p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-user-circle text-blue-600 text-4xl"></i>
            Customer Details
        </h1>
        <div class="flex gap-3">
            <a href="{{ url('customers') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ url('customers/' . $customer->customer_id . '/edit') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        {{-- Name --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Name</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $customer->name ?? '-' }}</p>
        </div>

        {{-- Plate Number --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Plate Number</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $customer->plate_number ?? '-' }}</p>
        </div>

        {{-- Phone Number --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Phone Number</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $customer->phone_number ?? '-' }}</p>
        </div>

        {{-- Vehicle Type --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Vehicle Type</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $customer->vehicleType->type_name ?? '-' }}</p>
        </div>

        {{-- Vehicle Color --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Vehicle Color</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $customer->vehicle_color ?? '-' }}</p>
        </div>

        {{-- Member Number --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Member Number</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $customer->member_number ?? '-' }}</p>
        </div>

        {{-- Join Date --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm flex flex-col justify-between h-full">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Join Date</h3>
                <p class="mt-1 text-gray-800">
                    {{ $customer->join_date ? $customer->join_date->format('d M Y') : '-' }}
                </p>
            </div>
        </div>

        {{-- Member Expiry Date --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm flex flex-col justify-between h-full">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Member Expiry Date</h3>
                <p class="mt-1 text-gray-800">
                    {{ $customer->member_expiry_date ? $customer->member_expiry_date->format('d M Y') : '-' }}
                </p>
            </div>
        </div>

         {{-- Created At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm flex flex-col justify-between h-full">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Created At</h3>
                <p class="mt-1 text-gray-800">
                    {{ $customer->created_at ? $customer->created_at->format('d M Y, H:i') : '-' }}
                </p>
            </div>
        </div>

        {{-- Updated At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm flex flex-col justify-between h-full">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Updated At</h3>
                <p class="mt-1 text-gray-800">
                    {{ $customer->updated_at ? $customer->updated_at->format('d M Y, H:i') : '-' }}
                </p>
            </div>
        </div>


        {{-- Membership Status --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Membership Status</h3>
            <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full font-semibold
                {{ $customer->is_member ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $customer->is_member ? 'Member' : 'Non-Member' }}
            </span>
        </div>
    </div>
</div>
@endsection
