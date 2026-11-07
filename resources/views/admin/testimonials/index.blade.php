@extends('layouts.admin')

@section('title', 'Manage Testimonials')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">Dashboard</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
    <span class="text-red-600 font-medium">Testimonials</span>
@endsection

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <i data-lucide="star" class="w-6 h-6 text-red-500"></i>
            Testimonials & Stories
        </h1>
        <p class="text-sm text-gray-500 mt-1">Review, approve, and curate donor and recipient community stories.</p>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Name & Location</th>
                    <th>Blood Group</th>
                    <th>Story / Quote</th>
                    <th>Submitted</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($testimonials as $item)
                    <tr>
                        <td>
                            @if($item->is_approved)
                                <span class="badge badge-green">Approved</span>
                            @else
                                <span class="badge badge-yellow">Pending</span>
                            @endif
                        </td>
                        <td>
                            <div class="font-semibold text-gray-900">{{ $item->name }}</div>
                            <div class="text-xs text-gray-400">{{ $item->location }}</div>
                        </td>
                        <td>
                            <span class="blood-group-badge text-xs">{{ $item->blood_group }}</span>
                        </td>
                        <td>
                            <p class="text-xs text-gray-700 max-w-md line-clamp-2">{{ $item->message }}</p>
                        </td>
                        <td class="text-xs text-gray-500 whitespace-nowrap">
                            {{ $item->created_at->format('d M, Y') }}
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                @if(!$item->is_approved)
                                    <form method="POST" action="{{ route('admin.testimonials.approve', $item) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg" title="Approve">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.testimonials.destroy', $item) }}"
                                      onsubmit="return confirm('Delete this testimonial?')" class="inline">
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
                        <td colspan="6" class="text-center py-10 text-gray-400">
                            No testimonials found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($testimonials->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $testimonials->links() }}
        </div>
    @endif
</div>

@endsection
