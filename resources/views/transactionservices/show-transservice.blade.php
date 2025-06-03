@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto mt-12 bg-white rounded-2xl shadow-md p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-concierge-bell text-blue-600 text-4xl"></i>
            Transaction Service Details
        </h1>
        <div class="flex gap-3">
            <a href="{{ route('transaction-services.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('transaction-services.edit', $transactionService->transaction_service_id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        {{-- Transaction Code --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Transaction Code</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">
                {{ $transactionService->transaction->transaction_code ?? '-' }}
            </p>
        </div>

        {{-- Service Name --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Service Name</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">
                {{ $transactionService->service->service_name ?? '-' }}
            </p>
        </div>

        {{-- Quantity --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Quantity</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $transactionService->quantity }}</p>
        </div>

        {{-- Unit Price --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Unit Price</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">
                Rp {{ number_format($transactionService->unit_price, 0, ',', '.') }}
            </p>
        </div>

        {{-- Discount --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Discount</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">
                Rp {{ number_format($transactionService->discount ?? 0, 0, ',', '.') }}
            </p>
        </div>

        {{-- Staff Name --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Staff</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">
                {{ $transactionService->staff->name ?? '-' }}
            </p>
        </div>

        {{-- Notes --}}
        <div class="sm:col-span-2 bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Notes</h3>
            <p class="mt-1 text-gray-900 whitespace-pre-line">{{ $transactionService->notes ?? '-' }}</p>
        </div>

        {{-- Start Time --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Start Time</h3>
            <p class="mt-1 text-gray-900">
                {{ $transactionService->start_time ? \Carbon\Carbon::parse($transactionService->start_time)->format('d M Y, H:i') : '-' }}
            </p>
        </div>

        {{-- End Time --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">End Time</h3>
            <p class="mt-1 text-gray-900">
                {{ $transactionService->end_time ? \Carbon\Carbon::parse($transactionService->end_time)->format('d M Y, H:i') : '-' }}
            </p>
        </div>

        {{-- Created At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Created At</h3>
            <p class="mt-1 text-gray-900">
                {{ $transactionService->created_at ? $transactionService->created_at->format('d M Y, H:i') : '-' }}
            </p>
        </div>

        {{-- Updated At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Updated At</h3>
            <p class="mt-1 text-gray-900">
                {{ $transactionService->updated_at ? $transactionService->updated_at->format('d M Y, H:i') : '-' }}
            </p>
        </div>

        {{-- Status --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Status</h3>
            <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full font-semibold
                @switch($transactionService->status)
                    @case('pending') bg-yellow-100 text-yellow-700 @break
                    @case('in_progress') bg-blue-100 text-blue-700 @break
                    @case('completed') bg-green-100 text-green-700 @break
                    @case('canceled') bg-red-100 text-red-700 @break
                    @default bg-gray-100 text-gray-700
                @endswitch
                ">
                {{ ucfirst(str_replace('_', ' ', $transactionService->status)) }}
            </span>
        </div>
    </div>
</div>
@endsection
