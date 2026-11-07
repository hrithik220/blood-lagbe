@extends('layouts.dashboard')

@section('title', 'Notifications')

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="{{ route('dashboard.index') }}" class="hover:text-red-600 transition-colors">Dashboard</a>
    <i data-lucide="chevron-right" class="w-4 h-4"></i>
    <span class="text-gray-900 font-medium">Notifications</span>
</nav>

{{-- Header --}}
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <i data-lucide="bell" class="w-6 h-6 text-red-500"></i>
            Notifications Center
        </h1>
        <p class="text-sm text-gray-500 mt-1">Updates on your blood requests, approvals, and matching emergencies.</p>
    </div>

    @if($notifications->count() > 0)
        <div>
            <form method="POST" action="{{ route('dashboard.notifications.mark-all-read') }}">
                @csrf
                <button type="submit" class="btn-outline text-xs py-2 px-3.5">
                    <i data-lucide="check-check" class="w-4 h-4"></i> Mark All as Read
                </button>
            </form>
        </div>
    @endif
</div>

<div class="max-w-4xl space-y-4">
    @forelse($notifications as $notification)
        @php
            $isUnread = $notification->read_at === null;
            $data = $notification->data;
            $title = $data['title'] ?? 'Notification';
            $message = $data['message'] ?? '';
            $url = $data['url'] ?? null;
            $type = $data['type'] ?? 'info';
        @endphp

        <div class="card p-5 transition-all hover:shadow-sm border-l-4 {{ $isUnread ? 'border-blood-600 bg-blood-50/20' : 'border-gray-200 bg-white' }}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $type === 'matching_request' ? 'bg-red-100 text-red-600' : 'bg-blood-100 text-blood-700' }}">
                        @if($type === 'matching_request')
                            <i data-lucide="activity" class="w-5 h-5"></i>
                        @elseif($type === 'request_approved')
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                        @else
                            <i data-lucide="bell" class="w-5 h-5"></i>
                        @endif
                    </div>

                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-gray-900">{{ $title }}</h3>
                            @if($isUnread)
                                <span class="badge badge-red text-[10px]">New</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ $message }}</p>

                        <div class="flex items-center gap-4 mt-3">
                            <span class="text-[11px] text-gray-400 flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </span>

                            @if($url)
                                <a href="{{ $url }}" class="text-xs font-semibold text-blood-700 hover:underline flex items-center gap-1">
                                    View Details <i data-lucide="external-link" class="w-3 h-3"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Delete single notification --}}
                <form method="POST" action="{{ route('dashboard.notifications.destroy', $notification->id) }}" onsubmit="return confirm('Delete this notification?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-gray-400 hover:text-red-600 p-1 rounded-lg transition-colors" title="Delete notification">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="card p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <i data-lucide="bell-off" class="w-8 h-8"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1">No Notifications Yet</h3>
            <p class="text-gray-500 text-xs">You're all caught up! When you have new updates on requests or matches, they will appear here.</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

@endsection
