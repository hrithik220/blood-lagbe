@extends('layouts.admin')

@section('title', 'Message from ' . $contactMessage->name)

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">Dashboard</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
    <a href="{{ route('admin.contacts.index') }}" class="hover:text-red-600">Contact Messages</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
    <span class="text-red-600 font-medium">Message #{{ $contactMessage->id }}</span>
@endsection

@section('content')

<div class="max-w-3xl">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Message Details</h1>
            <p class="text-xs text-gray-500 mt-1">Received on {{ $contactMessage->created_at->format('d M, Y h:i A') }}</p>
        </div>

        <form method="POST" action="{{ route('admin.contacts.destroy', $contactMessage) }}"
              onsubmit="return confirm('Delete this message permanently?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger text-xs py-2 px-3">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
            </button>
        </form>
    </div>

    <div class="card p-6 md:p-8 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-6 border-b border-gray-100 text-sm">
            <div>
                <span class="text-xs uppercase text-gray-400 font-semibold tracking-wider">From</span>
                <p class="font-bold text-gray-900 text-base mt-0.5">{{ $contactMessage->name }}</p>
                <a href="mailto:{{ $contactMessage->email }}" class="text-xs text-blood-700 hover:underline">
                    {{ $contactMessage->email }}
                </a>
            </div>

            <div>
                <span class="text-xs uppercase text-gray-400 font-semibold tracking-wider">Subject</span>
                <p class="font-bold text-gray-900 text-base mt-0.5">{{ $contactMessage->subject }}</p>
            </div>
        </div>

        <div>
            <span class="text-xs uppercase text-gray-400 font-semibold tracking-wider">Message Content</span>
            <div class="mt-2 p-5 bg-gray-50 rounded-2xl text-sm text-gray-800 leading-relaxed border border-gray-100 whitespace-pre-line">
                {{ $contactMessage->message }}
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <a href="{{ route('admin.contacts.index') }}" class="btn-outline text-xs">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Messages
            </a>

            <a href="mailto:{{ $contactMessage->email }}?subject={{ urlencode('Re: ' . $contactMessage->subject) }}"
               class="btn-primary text-xs py-2.5 px-4">
                <i data-lucide="mail-reply" class="w-4 h-4"></i> Reply via Email
            </a>
        </div>
    </div>
</div>

@endsection
