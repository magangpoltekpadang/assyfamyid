@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto mt-12 bg-white rounded-2xl shadow-md p-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-bell text-blue-600 text-4xl"></i>
            Notification Type Details
        </h1>
        <div class="flex gap-3">
            <a href="{{ url('notification-types') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ url('notification-types/' . $notificationType->notification_type_id . '/edit') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        {{-- Type Name --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Type Name</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $notificationType->type_name ?? '-' }}</p>
        </div>

        {{-- Code --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Code</h3>
            <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $notificationType->code ?? '-' }}</p>
        </div>

        {{-- Template Text --}}
        <div class="sm:col-span-2 bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Template Text</h3>
            <p class="mt-1 text-gray-800 whitespace-pre-line">{{ $notificationType->template_text ?? '-' }}</p>
        </div>

        {{-- Created At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm flex flex-col justify-between h-full">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Created At</h3>
                <p class="mt-1 text-gray-800">
                    {{ $notificationType->created_at ? $notificationType->created_at->format('d M Y, H:i') : '-' }}
                </p>
            </div>
        </div>

        {{-- Updated At --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm flex flex-col justify-between h-full">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Updated At</h3>
                <p class="mt-1 text-gray-800">
                    {{ $notificationType->updated_at ? $notificationType->updated_at->format('d M Y, H:i') : '-' }}
                </p>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Status</h3>
            <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full font-semibold
                {{ $notificationType->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $notificationType->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>
</div>
@endsection
