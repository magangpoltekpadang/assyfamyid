@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto mt-12 bg-white rounded-2xl shadow-md p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-concierge-bell text-blue-600 text-4xl"></i>
            Service Details
        </h1>
        <div class="flex gap-3">
            <a href="{{ url('services') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ url('services/' . $service->service_id . '/edit') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        {{-- Service Name --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Service Name</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $service->service_name ?? '-' }}</p>
        </div>

        {{-- Price --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Price</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">Rp {{ number_format($service->price, 2, ',', '.') }}</p>
        </div>

        {{-- Estimated Duration --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Estimated Duration (minutes)</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $service->estimated_duration ?? '-' }}</p>
        </div>

        {{-- Service Type --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Service Type</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">
                {{ $service->type->type_name ?? '-' }}
            </p>
        </div>

        {{-- Description --}}
        <div class="sm:col-span-2 bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Description</h3>
            <p class="mt-1 text-gray-800 whitespace-pre-line">{{ $service->description ?? '-' }}</p>
        </div>

        {{-- Outlet --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Outlet</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">
                {{ $service->outlet->outlet_name ?? '-' }}
            </p>
        </div>

        {{-- Status --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Status</h3>
            <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full font-semibold
                {{ $service->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $service->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        {{-- Created At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Created At</h3>
            <p class="mt-1 text-gray-800">
                {{ $service->created_at ? $service->created_at->format('d M Y, H:i') : '-' }}
            </p>
        </div>

        {{-- Updated At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Updated At</h3>
            <p class="mt-1 text-gray-800">
                {{ $service->updated_at ? $service->updated_at->format('d M Y, H:i') : '-' }}
            </p>
        </div>
    </div>
</div>
@endsection
