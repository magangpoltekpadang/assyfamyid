@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-2xl">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Add Transaction Service</h2>

    <form method="POST" action="{{ route('transaction-services.store') }}">
        @csrf

        {{-- Transaction --}}
        <div class="mb-4">
            <label for="transaction_id" class="block text-sm font-medium text-gray-700">Transaction</label>
            <select id="transaction_id" name="transaction_id"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 shadow-sm" required>
                <option value="">-- Select Transaction --</option>
                @foreach ($transactions as $transaction)
                    <option value="{{ $transaction->transaction_id }}">
                        {{ $transaction->transaction_code }} - {{ $transaction->transaction_date }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Service --}}
        <div class="mb-4">
            <label for="service_id" class="block text-sm font-medium text-gray-700">Service</label>
            <select id="service_id" name="service_id"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 shadow-sm" required>
                <option value="">-- Select Service --</option>
                @foreach ($services as $service)
                    <option value="{{ $service->service_id }}">{{ $service->service_name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Quantity --}}
        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
            <input type="number" id="quantity" name="quantity" min="1"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 shadow-sm" required>
        </div>

        {{-- Unit Price --}}
        <div class="mb-4">
            <label for="unit_price" class="block text-sm font-medium text-gray-700">Unit Price</label>
            <input type="number" id="unit_price" name="unit_price" step="0.01" min="0"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 shadow-sm" required>
        </div>

        {{-- Discount --}}
        <div class="mb-4">
            <label for="discount" class="block text-sm font-medium text-gray-700">Discount</label>
            <input type="number" id="discount" name="discount" step="0.01" min="0"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 shadow-sm">
        </div>

        {{-- Staff --}}
        <div class="mb-4">
            <label for="staff_id" class="block text-sm font-medium text-gray-700">Staff</label>
            <select id="staff_id" name="staff_id"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 shadow-sm" required>
                <option value="">-- Select Staff --</option>
                @foreach ($staffs as $staff)
                    <option value="{{ $staff->staff_id }}">{{ $staff->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Start Time --}}
        <div class="mb-4">
            <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input type="datetime-local" id="start_time" name="start_time"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 shadow-sm" required>
        </div>

        {{-- End Time --}}
        <div class="mb-4">
            <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
            <input type="datetime-local" id="end_time" name="end_time"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 shadow-sm" required>
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select id="status" name="status"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 shadow-sm" required>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="canceled">Canceled</option>
            </select>
        </div>

        {{-- Notes --}}
        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea id="notes" name="notes" rows="3"
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 shadow-sm resize-none"></textarea>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Save
            </button>
        </div>
    </form>
</div>
@endsection
