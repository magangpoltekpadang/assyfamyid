@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto mt-12 bg-white rounded-2xl shadow-md p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-receipt text-blue-600 text-4xl"></i>
            Transaction Details
        </h1>
        <div class="flex gap-3">
            <a href="{{ route('transactions.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('transactions.edit', $transaction->transaction_id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

        {{-- Transaction Code --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Transaction Code</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $transaction->transaction_code ?? '-' }}</p>
        </div>

        {{-- Transaction Date --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Transaction Date</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $transaction->transaction_date ? $transaction->transaction_date->format('d M Y, H:i') : '-' }}</p>
        </div>

        {{-- Customer --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Customer</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $transaction->customer?->name ?? '-' }}</p>
        </div>

        {{-- Outlet --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Outlet</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $transaction->outlet?->outlet_name ?? '-' }}</p>
        </div>

        {{-- Staff --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Staff</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $transaction->staff?->name ?? '-' }}</p>
        </div>

        {{-- Shift --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Shift</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $transaction->shift?->shift_name ?? '-' }}</p>
        </div>

        {{-- Subtotal --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Subtotal</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ 'Rp ' . number_format($transaction->subtotal ?? 0, 0, ',', '.') }}</p>
        </div>

        {{-- Discount --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Discount</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ 'Rp ' . number_format($transaction->discount ?? 0, 0, ',', '.') }}</p>
        </div>

        {{-- Tax --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Tax</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ 'Rp ' . number_format($transaction->tax ?? 0, 0, ',', '.') }}</p>
        </div>

        {{-- Final Price --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Final Price</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ 'Rp ' . number_format($transaction->final_price ?? 0, 0, ',', '.') }}</p>
        </div>

        {{-- Payment Status --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Payment Status</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $transaction->paymentStatus?->status_name ?? '-' }}</p>
        </div>

        {{-- Gate Opened --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Gate Opened</h3>
            <p class="mt-1 text-lg font-semibold {{ $transaction->gate_opened ? 'text-green-600' : 'text-red-600' }}">
                {{ $transaction->gate_opened ? 'Yes' : 'No' }}
            </p>
        </div>

        {{-- Notes --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm col-span-1 sm:col-span-2">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Notes</h3>
            <p class="mt-1 text-gray-800 whitespace-pre-wrap">{{ $transaction->notes ?? '-' }}</p>
        </div>

        {{-- Receipt Printed --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Receipt Printed</h3>
            <p class="mt-1 text-lg font-semibold {{ $transaction->receipt_printed ? 'text-green-600' : 'text-red-600' }}">
                {{ $transaction->receipt_printed ? 'Yes' : 'No' }}
            </p>
        </div>

        {{-- WhatsApp Sent --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">WhatsApp Sent</h3>
            <p class="mt-1 text-lg font-semibold {{ $transaction->whatsapp_sent ? 'text-green-600' : 'text-red-600' }}">
                {{ $transaction->whatsapp_sent ? 'Yes' : 'No' }}
            </p>
        </div>

        {{-- Created At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Created At</h3>
            <p class="mt-1 text-gray-800">{{ $transaction->created_at ? $transaction->created_at->format('d M Y, H:i') : '-' }}</p>
        </div>

        {{-- Updated At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Updated At</h3>
            <p class="mt-1 text-gray-800">{{ $transaction->updated_at ? $transaction->updated_at->format('d M Y, H:i') : '-' }}</p>
        </div>

    </div>
</div>
@endsection
