@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto mt-12 bg-white rounded-2xl shadow-md p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-tools text-blue-600 text-4xl"></i>
            Service Type Details
        </h1>
        <div class="flex gap-3">
            <a href="{{ url('service-types') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ url('service-types/' . $serviceType->service_type_id . '/edit') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        {{-- Type Name --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Type Name</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $serviceType->type_name ?? '-' }}</p>
        </div>

        {{-- Code --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Code</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $serviceType->code ?? '-' }}</p>
        </div>

        {{-- Description --}}
        <div class="sm:col-span-2 bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Description</h3>
            <p class="mt-1 text-gray-800 whitespace-pre-line">{{ $serviceType->description ?? '-' }}</p>
        </div>

        {{-- Created At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm flex flex-col justify-between h-full">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Created At</h3>
                <p class="mt-1 text-gray-800">
                    {{ $serviceType->created_at ? $serviceType->created_at->format('d M Y, H:i') : '-' }}
                </p>
            </div>
        </div>

        {{-- Updated At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm flex flex-col justify-between h-full">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Updated At</h3>
                <p class="mt-1 text-gray-800">
                    {{ $serviceType->updated_at ? $serviceType->updated_at->format('d M Y, H:i') : '-' }}
                </p>
            </div>
        </div>

         {{-- Status --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Status</h3>
            <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full font-semibold
                {{ $serviceType->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $serviceType->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>
</div>
@endsection
