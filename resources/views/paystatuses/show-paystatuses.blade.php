@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto mt-12 bg-white rounded-2xl shadow-md p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-info-circle text-blue-600 text-4xl"></i>
            Payment Status Details
        </h1>
        <div class="flex gap-3">
            <a href="{{ url('payment-statuses') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ url('payment-statuses/' . $paymentStatus->payment_status_id . '/edit') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        {{-- Status Name --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Status Name</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $paymentStatus->status_name ?? '-' }}</p>
        </div>

        {{-- Code --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Code</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $paymentStatus->code ?? '-' }}</p>
        </div>

        {{-- Description --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm sm:col-span-2">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Description</h3>
            <p class="mt-1 text-gray-800 whitespace-pre-line">{{ $paymentStatus->description ?? '-' }}</p>
        </div>

        {{-- Created At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Created At</h3>
            <p class="mt-1 text-gray-800">
                {{ $paymentStatus->created_at ? $paymentStatus->created_at->format('d M Y, H:i') : '-' }}
            </p>
        </div>

        {{-- Updated At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Updated At</h3>
            <p class="mt-1 text-gray-800">
                {{ $paymentStatus->updated_at ? $paymentStatus->updated_at->format('d M Y, H:i') : '-' }}
            </p>
        </div>
    </div>
</div>
@endsection
