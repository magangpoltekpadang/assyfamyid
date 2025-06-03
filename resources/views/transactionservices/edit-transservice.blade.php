@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-2xl">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Edit Transaction Service</h2>

    <form method="POST" action="{{ route('transaction-services.update', $transactionService) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="transaction_id" class="block text-sm font-medium text-gray-700">Transaction</label>
            <select name="transaction_id" id="transaction_id" class="w-full p-2 rounded border border-gray-300">
                @foreach ($transactions as $transaction)
                    <option value="{{ $transaction->transaction_id }}" {{ $transactionService->transaction_id == $transaction->transaction_id ? 'selected' : '' }}>
                        {{ $transaction->transaction_code }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="service_id" class="block text-sm font-medium text-gray-700">Service</label>
            <select name="service_id" id="service_id" class="w-full p-2 rounded border border-gray-300">
                @foreach ($services as $service)
                    <option value="{{ $service->service_id }}" {{ $transactionService->service_id == $service->service_id ? 'selected' : '' }}>
                        {{ $service->service_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $transactionService->quantity) }}"
                class="w-full p-2 border rounded border-gray-300" required>
        </div>

        <div class="mb-4">
            <label for="unit_price" class="block text-sm font-medium text-gray-700">Unit Price</label>
            <input type="number" step="0.01" name="unit_price" id="unit_price" value="{{ old('unit_price', $transactionService->unit_price) }}"
                class="w-full p-2 border rounded border-gray-300" required>
        </div>

        <div class="mb-4">
            <label for="discount" class="block text-sm font-medium text-gray-700">Discount</label>
            <input type="number" step="0.01" name="discount" id="discount" value="{{ old('discount', $transactionService->discount) }}"
                class="w-full p-2 border rounded border-gray-300">
        </div>

        <div class="mb-4">
            <label for="staff_id" class="block text-sm font-medium text-gray-700">Staff</label>
            <select name="staff_id" id="staff_id" class="w-full p-2 rounded border border-gray-300">
                @foreach ($staffs as $staff)
                    <option value="{{ $staff->staff_id }}" {{ $transactionService->staff_id == $staff->staff_id ? 'selected' : '' }}>
                        {{ $staff->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($transactionService->start_time)->format('Y-m-d\TH:i')) }}"
                class="w-full p-2 border rounded border-gray-300" required>
        </div>

        <div class="mb-4">
            <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
            <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($transactionService->end_time)->format('Y-m-d\TH:i')) }}"
                class="w-full p-2 border rounded border-gray-300" required>
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status" class="w-full p-2 rounded border border-gray-300">
                @foreach (['pending', 'in progress', 'completed', 'canceled'] as $status)
                    <option value="{{ $status }}" {{ $transactionService->status == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea name="notes" id="notes" rows="3"
                class="w-full p-2 rounded border border-gray-300">{{ old('notes', $transactionService->notes) }}</textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
