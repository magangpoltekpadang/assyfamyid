@extends('layouts.main')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-2xl">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Edit Staff</h2>

    <form method="POST" action="{{ url('staff/' . $staff->staff_id) }}">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $staff->name) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                required>
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $staff->email) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                required>
        </div>

        {{-- Phone Number --}}
        <div class="mb-4">
            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $staff->phone_number) }}"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                required>
        </div>

        {{-- Role --}}
        <div class="mb-4">
            <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
            <select id="role_id" name="role_id" required
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                @foreach ($roles as $role)
                    <option value="{{ $role->role_id }}" {{ $staff->role_id == $role->role_id ? 'selected' : '' }}>
                        {{ $role->role_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Outlet --}}
        <div class="mb-4">
            <label for="outlet_id" class="block text-sm font-medium text-gray-700">Outlet</label>
            <select id="outlet_id" name="outlet_id" required
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                @foreach ($outlets as $outlet)
                    <option value="{{ $outlet->outlet_id }}" {{ $staff->outlet_id == $outlet->outlet_id ? 'selected' : '' }}>
                        {{ $outlet->outlet_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password <small>(Leave blank to keep current)</small></label>
            <input type="password" id="password" name="password"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
        </div>

        {{-- Active Status --}}
        <div class="mb-6">
            <label for="is_active" class="block text-sm font-medium text-gray-700">Active Status</label>
            <select id="is_active" name="is_active"
                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                <option value="1" {{ $staff->is_active == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $staff->is_active == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
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
