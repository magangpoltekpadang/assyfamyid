@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto mt-12 bg-white rounded-2xl shadow-md p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-clock text-blue-600 text-4xl"></i>
            Shift Details
        </h1>
        <div class="flex gap-3">
            <a href="{{ url('shifts') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ url('shifts/' . $shift->shift_id . '/edit') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        {{-- Shift Name --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Shift Name</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $shift->shift_name ?? '-' }}</p>
        </div>

        {{-- Outlet ID --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Outlet ID</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $shift->outlet_id ?? '-' }}</p>
        </div>

        {{-- Start Time --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Start Time</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $shift->start_time ?? '-' }}</p>
        </div>

        {{-- End Time --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">End Time</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $shift->end_time ?? '-' }}</p>
        </div>

        {{-- Created At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Created At</h3>
            <p class="mt-1 text-gray-800">
                {{ $shift->created_at ? $shift->created_at->format('d M Y, H:i') : '-' }}
            </p>
        </div>

        {{-- Updated At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Updated At</h3>
            <p class="mt-1 text-gray-800">
                {{ $shift->updated_at ? $shift->updated_at->format('d M Y, H:i') : '-' }}
            </p>
        </div>

        {{-- Status --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Status</h3>
            <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full font-semibold
                {{ $shift->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $shift->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>
</div>
@endsection
