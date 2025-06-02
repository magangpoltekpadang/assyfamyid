@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-2xl">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Edit Shift</h2>

    <form method="POST" action="{{ url('shift/' . $shift->shift_id) }}">
        @csrf
        @method('PUT')

        {{-- Shift Name --}}
        <div class="mb-4">
            <label for="shift_name" class="block text-sm font-medium text-gray-700">Shift Name</label>
            <input type="text" id="shift_name" name="shift_name" value="{{ old('shift_name', $shift->shift_name) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                maxlength="50" required>
            @error('shift_name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Outlet --}}
        <div class="mb-4">
            <label for="outlet_id" class="block text-sm font-medium text-gray-700">Outlet</label>
            <select id="outlet_id" name="outlet_id" required
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                <option value="">-- Select Outlet --</option>
                @foreach ($outlets as $outlet)
                    <option value="{{ $outlet->outlet_id }}" {{ (old('outlet_id', $shift->outlet_id) == $outlet->outlet_id) ? 'selected' : '' }}>
                        {{ $outlet->outlet_name }}
                    </option>
                @endforeach
            </select>
            @error('outlet_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Start Time --}}
        <div class="mb-4">
            <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input type="time" id="start_time" name="start_time"
                value="{{ old('start_time', \Carbon\Carbon::parse($shift->start_time)->format('H:i')) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                required>
            @error('start_time')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- End Time --}}
        <div class="mb-4">
            <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
            <input type="time" id="end_time" name="end_time"
                value="{{ old('end_time', \Carbon\Carbon::parse($shift->end_time)->format('H:i')) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                required>
            @error('end_time')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Active Status --}}
        <div class="mb-6">
            <label for="is_active" class="block text-sm font-medium text-gray-700">Active Status</label>
            <select id="is_active" name="is_active"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                <option value="1" {{ (old('is_active', $shift->is_active) == 1) ? 'selected' : '' }}>Active</option>
                <option value="0" {{ (old('is_active', $shift->is_active) == 0) ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('is_active')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
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
