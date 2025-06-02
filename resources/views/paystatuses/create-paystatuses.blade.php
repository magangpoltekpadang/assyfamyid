@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-2xl">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Add Payment Method</h2>

    <form method="POST" action="/payment-methods">
        @csrf

        {{-- Method Name --}}
        <div class="mb-4">
            <label for="method_name" class="block text-sm font-medium text-gray-700">Method Name</label>
            <input type="text" id="method_name" name="method_name"
                   value="{{ old('method_name') }}"
                   class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2"
                   required>
            @error('method_name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Code --}}
        <div class="mb-4">
            <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
            <input type="text" id="code" name="code"
                   value="{{ old('code') }}"
                   class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
            @error('code')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Active Status --}}
        <div class="mb-6">
            <label for="is_active" class="block text-sm font-medium text-gray-700">Active Status</label>
            <select id="is_active" name="is_active"
                    class="mt-1 block w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-2">
                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('is_active')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
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
