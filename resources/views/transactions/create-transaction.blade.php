@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-2xl">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Add Transaction</h2>

    <form method="POST" action="{{ route('transactions.store') }}">
        @csrf

        {{-- Customer --}}
        <div class="mb-4">
            <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
            <select id="customer_id" name="customer_id" class="mt-1 block w-full border rounded-md p-2 shadow-sm">
                <option value="">-- Select Customer --</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->customer_id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Outlet --}}
        <div class="mb-4">
            <label for="outlet_id" class="block text-sm font-medium text-gray-700">Outlet</label>
            <select id="outlet_id" name="outlet_id" class="mt-1 block w-full border rounded-md p-2 shadow-sm">
                <option value="">-- Select Outlet --</option>
                @foreach ($outlets as $outlet)
                    <option value="{{ $outlet->outlet_id }}">{{ $outlet->outlet_name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Transaction Date --}}
        <div class="mb-4">
            <label for="transaction_date" class="block text-sm font-medium text-gray-700">Transaction Date</label>
            <input type="datetime-local" name="transaction_date" id="transaction_date" class="mt-1 block w-full border rounded-md p-2 shadow-sm">
        </div>

        {{-- Subtotal, Discount, Tax --}}
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div>
                <label for="subtotal" class="block text-sm font-medium text-gray-700">Subtotal</label>
                <input type="number" step="0.01" name="subtotal" id="subtotal" class="mt-1 block w-full border rounded-md p-2 shadow-sm">
            </div>
            <div>
                <label for="discount" class="block text-sm font-medium text-gray-700">Discount</label>
                <input type="number" step="0.01" name="discount" id="discount" class="mt-1 block w-full border rounded-md p-2 shadow-sm">
            </div>
            <div>
                <label for="tax" class="block text-sm font-medium text-gray-700">Tax</label>
                <input type="number" step="0.01" name="tax" id="tax" class="mt-1 block w-full border rounded-md p-2 shadow-sm">
            </div>
        </div>

        {{-- Payment Status --}}
        <div class="mb-4">
            <label for="payment_status_id" class="block text-sm font-medium text-gray-700">Payment Status</label>
            <select id="payment_status_id" name="payment_status_id" class="mt-1 block w-full border rounded-md p-2 shadow-sm">
                <option value="">-- Select Status --</option>
                @foreach ($paymentStatuses as $status)
                    <option value="{{ $status->payment_status_id }}">{{ $status->status_name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Staff --}}
        <div class="mb-4">
            <label for="staff_id" class="block text-sm font-medium text-gray-700">Staff</label>
            <select id="staff_id" name="staff_id" class="mt-1 block w-full border rounded-md p-2 shadow-sm">
                <option value="">-- Select Staff --</option>
                @foreach ($staffs as $staff)
                    <option value="{{ $staff->staff_id }}">{{ $staff->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Shift --}}
        <div class="mb-4">
            <label for="shift_id" class="block text-sm font-medium text-gray-700">Shift</label>
            <select id="shift_id" name="shift_id" class="mt-1 block w-full border rounded-md p-2 shadow-sm">
                <option value="">-- Select Shift --</option>
                @foreach ($shifts as $shift)
                    <option value="{{ $shift->shift_id }}">{{ $shift->shift_name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Gate Opened, Receipt Printed, WhatsApp Sent --}}
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Gate Opened</label>
                <select name="gate_opened" class="w-full border rounded-md p-2 shadow-sm">
                    <option value="">--</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Receipt Printed</label>
                <select name="receipt_printed" class="w-full border rounded-md p-2 shadow-sm">
                    <option value="">--</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">WhatsApp Sent</label>
                <select name="whatsapp_sent" class="w-full border rounded-md p-2 shadow-sm">
                    <option value="">--</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>

        {{-- Notes --}}
        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border rounded-md p-2 shadow-sm resize-none"></textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                Save
            </button>
        </div>
    </form>
</div>
@endsection
