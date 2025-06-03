@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-2xl">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Edit Transaction</h2>

    <form method="POST" action="{{ url('transactions/' . $transaction->transaction_id) }}">
        @csrf
        @method('PUT')

        {{-- Transaction Code --}}
        <div class="mb-4">
            <label for="transaction_code" class="block text-sm font-medium text-gray-700">Transaction Code</label>
            <input type="text" id="transaction_code" name="transaction_code" required
                value="{{ old('transaction_code', $transaction->transaction_code) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm">
        </div>

        {{-- Customer --}}
        <div class="mb-4">
            <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
            <select id="customer_id" name="customer_id" required
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm">
                <option value="">-- Select Customer --</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->customer_id }}" {{ $transaction->customer_id == $customer->customer_id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Outlet --}}
        <div class="mb-4">
            <label for="outlet_id" class="block text-sm font-medium text-gray-700">Outlet</label>
            <select id="outlet_id" name="outlet_id" required
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm">
                <option value="">-- Select Outlet --</option>
                @foreach ($outlets as $outlet)
                    <option value="{{ $outlet->outlet_id }}" {{ $transaction->outlet_id == $outlet->outlet_id ? 'selected' : '' }}>
                        {{ $outlet->outlet_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Transaction Date --}}
        <div class="mb-4">
            <label for="transaction_date" class="block text-sm font-medium text-gray-700">Transaction Date</label>
            <input type="datetime-local" id="transaction_date" name="transaction_date" required
                value="{{ old('transaction_date', \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d\TH:i')) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm">
        </div>

        {{-- Subtotal --}}
        <div class="mb-4">
            <label for="subtotal" class="block text-sm font-medium text-gray-700">Subtotal</label>
            <input type="number" step="0.01" id="subtotal" name="subtotal" required
                value="{{ old('subtotal', $transaction->subtotal) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm">
        </div>

        {{-- Discount --}}
        <div class="mb-4">
            <label for="discount" class="block text-sm font-medium text-gray-700">Discount</label>
            <input type="number" step="0.01" id="discount" name="discount"
                value="{{ old('discount', $transaction->discount) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm">
        </div>

        {{-- Tax --}}
        <div class="mb-4">
            <label for="tax" class="block text-sm font-medium text-gray-700">Tax</label>
            <input type="number" step="0.01" id="tax" name="tax"
                value="{{ old('tax', $transaction->tax) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm">
        </div>

        {{-- Final Price --}}
        <div class="mb-4">
            <label for="final_price" class="block text-sm font-medium text-gray-700">Final Price</label>
            <input type="number" step="0.01" id="final_price" name="final_price" readonly
                class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 p-2 shadow-sm cursor-not-allowed"
                value="{{ old('final_price', $transaction->final_price) }}">
        </div>

        {{-- Payment Status --}}
        <div class="mb-4">
            <label for="payment_status_id" class="block text-sm font-medium text-gray-700">Payment Status</label>
            <select id="payment_status_id" name="payment_status_id" required
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm">
                <option value="">-- Select Status --</option>
                @foreach ($paymentStatuses as $status)
                    <option value="{{ $status->payment_status_id }}" {{ $transaction->payment_status_id == $status->payment_status_id ? 'selected' : '' }}>
                        {{ $status->status_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Gate Opened --}}
        <div class="mb-4">
            <label for="gate_opened" class="block text-sm font-medium text-gray-700">Gate Opened</label>
            <select id="gate_opened" name="gate_opened" class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm">
                <option value="1" {{ $transaction->gate_opened ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$transaction->gate_opened ? 'selected' : '' }}>No</option>
            </select>
        </div>

        {{-- Staff --}}
        <div class="mb-4">
            <label for="staff_id" class="block text-sm font-medium text-gray-700">Staff</label>
            <select id="staff_id" name="staff_id" class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm">
                <option value="">-- Select Staff --</option>
                @foreach ($staffs as $staff)
                    <option value="{{ $staff->staff_id }}" {{ $transaction->staff_id == $staff->staff_id ? 'selected' : '' }}>
                        {{ $staff->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Shift --}}
        <div class="mb-4">
            <label for="shift_id" class="block text-sm font-medium text-gray-700">Shift</label>
            <select id="shift_id" name="shift_id" class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm">
                <option value="">-- Select Shift --</option>
                @foreach ($shifts as $shift)
                    <option value="{{ $shift->shift_id }}" {{ $transaction->shift_id == $shift->shift_id ? 'selected' : '' }}>
                        {{ $shift->shift_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Receipt Printed --}}
        <div class="mb-4">
            <label for="receipt_printed" class="block text-sm font-medium text-gray-700">Receipt Printed</label>
            <select id="receipt_printed" name="receipt_printed" class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm">
                <option value="1" {{ $transaction->receipt_printed ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$transaction->receipt_printed ? 'selected' : '' }}>No</option>
            </select>
        </div>

        {{-- WhatsApp Sent --}}
        <div class="mb-4">
            <label for="whatsapp_sent" class="block text-sm font-medium text-gray-700">WhatsApp Sent</label>
            <select id="whatsapp_sent" name="whatsapp_sent" class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm">
                <option value="1" {{ $transaction->whatsapp_sent ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$transaction->whatsapp_sent ? 'selected' : '' }}>No</option>
            </select>
        </div>

        {{-- Notes --}}
        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea id="notes" name="notes" rows="3"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm">{{ old('notes', $transaction->notes) }}</textarea>
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
