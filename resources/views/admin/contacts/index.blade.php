@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">Dashboard</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
    <span class="text-red-600 font-medium">Contact Messages</span>
@endsection

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <i data-lucide="mail" class="w-6 h-6 text-red-500"></i>
            Contact Messages
        </h1>
        <p class="text-sm text-gray-500 mt-1">Review inquiries, assistance requests, and community messages.</p>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Sender</th>
                    <th>Subject</th>
                    <th>Received</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr class="{{ !$msg->is_read ? 'bg-red-50/30 font-medium' : '' }}">
                        <td>
                            @if(!$msg->is_read)
                                <span class="badge badge-red">New</span>
                            @else
                                <span class="badge badge-gray">Read</span>
                            @endif
                        </td>
                        <td>
                            <div class="font-semibold text-gray-900">{{ $msg->name }}</div>
                            <div class="text-xs text-gray-400">{{ $msg->email }}</div>
                        </td>
                        <td>
                            <div class="text-sm text-gray-800">{{ $msg->subject }}</div>
                            <div class="text-xs text-gray-400 line-clamp-1">{{ Str::limit($msg->message, 80) }}</div>
                        </td>
                        <td class="text-xs text-gray-500 whitespace-nowrap">
                            {{ $msg->created_at->diffForHumans() }}
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.contacts.show', $msg) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Read Message">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>

                                <form method="POST" action="{{ route('admin.contacts.destroy', $msg) }}"
                                      onsubmit="return confirm('Delete this message?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg" title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-400">
                            No contact messages received yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($messages->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $messages->links() }}
        </div>
    @endif
</div>

@endsection
