@extends('layouts.admin')

@section('title', 'Manage FAQs')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">Dashboard</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
    <span class="text-red-600 font-medium">FAQs</span>
@endsection

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <i data-lucide="help-circle" class="w-6 h-6 text-red-500"></i>
            Frequently Asked Questions
        </h1>
        <p class="text-sm text-gray-500 mt-1">Manage platform questions and answers visible to the public.</p>
    </div>

    <div>
        <a href="{{ route('admin.faqs.create') }}" class="btn-primary text-sm py-2 px-4 shadow-sm">
            <i data-lucide="plus" class="w-4 h-4"></i> Add New FAQ
        </a>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Category</th>
                    <th>Question</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                    <tr>
                        <td class="font-mono text-xs text-gray-500 font-bold">#{{ $faq->sort_order }}</td>
                        <td>
                            <span class="badge badge-blue text-[11px] capitalize">{{ $faq->category }}</span>
                        </td>
                        <td>
                            <div class="font-semibold text-gray-900 text-sm">{{ $faq->question }}</div>
                            <div class="text-xs text-gray-400 max-w-xl line-clamp-1 mt-0.5">{{ Str::limit($faq->answer, 100) }}</div>
                        </td>
                        <td>
                            @if($faq->is_active)
                                <span class="badge badge-green">Active</span>
                            @else
                                <span class="badge badge-gray">Hidden</span>
                            @endif
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>

                                <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}"
                                      onsubmit="return confirm('Delete this FAQ entry?')" class="inline">
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
                            No FAQ entries found. Add your first question above!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($faqs->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $faqs->links() }}
        </div>
    @endif
</div>

@endsection
