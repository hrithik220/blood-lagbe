@extends('layouts.admin')

@section('title', 'Manage Blood Requests')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">Dashboard</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
    <span class="text-red-600 font-medium">Blood Requests</span>
@endsection

@section('content')

{{-- Header --}}
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <i data-lucide="droplet" class="w-6 h-6 text-red-500"></i>
            Blood Requests Management
        </h1>
        <p class="text-sm text-gray-500 mt-1">Review, approve, fulfill, or manage patient blood requests.</p>
    </div>
</div>

{{-- Filters --}}
<div class="card p-5 mb-6">
    <form method="GET" action="{{ route('admin.requests.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
            <label for="search" class="label">Search</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}"
                   placeholder="Patient, hospital, district..." class="input text-sm py-2">
        </div>

        <div>
            <label for="status" class="label">Status</label>
            <select id="status" name="status" class="select text-sm py-2">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="fulfilled" {{ request('status') === 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <div>
            <label for="blood_group" class="label">Blood Group</label>
            <select id="blood_group" name="blood_group" class="select text-sm py-2">
                <option value="">All Groups</option>
                @foreach($bloodGroups as $bg)
                    <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="emergency_level" class="label">Urgency</label>
            <select id="emergency_level" name="emergency_level" class="select text-sm py-2">
                <option value="">All Levels</option>
                <option value="critical" {{ request('emergency_level') === 'critical' ? 'selected' : '' }}>Critical</option>
                <option value="urgent" {{ request('emergency_level') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                <option value="normal" {{ request('emergency_level') === 'normal' ? 'selected' : '' }}>Normal</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary text-sm py-2 px-4 justify-center w-full">
                <i data-lucide="filter" class="w-4 h-4"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'blood_group', 'emergency_level']))
                <a href="{{ route('admin.requests.index') }}" class="btn-outline text-sm py-2 px-3 text-gray-500" title="Reset">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Requests Table --}}
<div class="card overflow-hidden">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Blood Group</th>
                    <th>Hospital / District</th>
                    <th>Emergency</th>
                    <th>Status</th>
                    <th>Required Date</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                    <tr>
                        <td class="font-mono text-xs text-gray-400">#{{ $request->id }}</td>
                        <td>
                            <div class="font-semibold text-gray-900">{{ $request->patient_name }}</div>
                            <div class="text-xs text-gray-400">By: {{ $request->user->name ?? 'Unknown' }}</div>
                        </td>
                        <td>
                            <span class="blood-group-badge">
                                {{ $request->blood_group }}
                            </span>
                        </td>
                        <td>
                            <div class="text-sm text-gray-800 font-medium">{{ $request->hospital_name }}</div>
                            <div class="text-xs text-gray-500">{{ $request->district }}</div>
                        </td>
                        <td>
                            @if($request->emergency_level === 'critical')
                                <span class="badge badge-red">Critical</span>
                            @elseif($request->emergency_level === 'urgent')
                                <span class="badge badge-orange">Urgent</span>
                            @else
                                <span class="badge badge-gray">Normal</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $request->status_badge_color }} capitalize">
                                {{ $request->status }}
                            </span>
                        </td>
                        <td class="text-xs text-gray-600 whitespace-nowrap">
                            {{ $request->required_date->format('d M, Y') }}
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                {{-- Quick Approve if pending --}}
                                @if($request->status === 'pending')
                                    <form method="POST" action="{{ route('admin.requests.approve', $request) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg" title="Approve Request">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.requests.show', $request) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="View Details">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>

                                <form method="POST" action="{{ route('admin.requests.destroy', $request) }}"
                                      onsubmit="return confirm('Permanently delete this blood request?')" class="inline">
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
                        <td colspan="8" class="text-center py-10 text-gray-400">
                            No blood requests found matching your filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($requests->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $requests->links() }}
        </div>
    @endif
</div>

@endsection
