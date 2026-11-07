@extends('layouts.dashboard')

@section('title', 'My Blood Requests')

@section('content')

{{-- Page Header --}}
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <i data-lucide="droplet" class="w-6 h-6 text-red-500"></i>
            My Blood Requests
        </h1>
        <p class="text-sm text-gray-500 mt-1">Track and manage all your blood requests.</p>
    </div>
    <a href="{{ route('dashboard.requests.create') }}" class="btn-primary flex items-center gap-2 self-start sm:self-auto">
        <i data-lucide="plus" class="w-4 h-4"></i>
        New Request
    </a>
</div>

{{-- Filter Bar --}}
<div class="card mb-6" x-data="{}" @submit.prevent>
    <form method="GET" action="{{ route('dashboard.requests.index') }}" class="flex flex-col sm:flex-row gap-3">
        <!-- Search -->
        <div class="flex-1 relative">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by patient name or hospital..."
                class="input pl-9 w-full"
            >
        </div>

        <!-- Status Filter -->
        <div class="sm:w-44">
            <select name="status" class="select w-full">
                <option value="">All Statuses</option>
                @foreach(['pending','approved','in_progress','fulfilled','cancelled','rejected'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Blood Group Filter -->
        <div class="sm:w-36">
            <select name="blood_group" class="select w-full">
                <option value="">All Groups</option>
                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                    <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn-primary flex items-center gap-1.5">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>
            @if(request()->hasAny(['search','status','blood_group']))
                <a href="{{ route('dashboard.requests.index') }}" class="btn-outline flex items-center gap-1.5">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Clear
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Requests Table --}}
<div class="card">
    @if($requests->count() > 0)
        <div class="table-container">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Blood</th>
                        <th>Hospital / District</th>
                        <th>Status</th>
                        <th>Required Date</th>
                        <th>Emergency</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                    @php
                        $statusClasses = [
                            'pending'     => 'badge-yellow',
                            'approved'    => 'badge-blue',
                            'in_progress' => 'badge-purple',
                            'fulfilled'   => 'badge-green',
                            'cancelled'   => 'badge-gray',
                            'rejected'    => 'badge-red',
                        ];
                        $emergencyClasses = [
                            'normal'   => 'badge-gray',
                            'urgent'   => 'badge-orange',
                            'critical' => 'badge-red',
                        ];
                    @endphp
                    <tr>
                        <td>
                            <p class="font-medium text-gray-900 text-sm">{{ $request->patient_name }}</p>
                            <p class="text-xs text-gray-400">#{{ $request->id }}</p>
                        </td>
                        <td>
                            <span class="blood-group-badge">{{ $request->blood_group }}</span>
                        </td>
                        <td>
                            <p class="text-sm text-gray-700">{{ $request->hospital_name }}</p>
                            <p class="text-xs text-gray-400">{{ $request->district }}</p>
                        </td>
                        <td>
                            <span class="badge {{ $statusClasses[$request->status] ?? 'badge-gray' }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td class="text-sm text-gray-600 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($request->required_date)->format('d M Y') }}
                        </td>
                        <td>
                            <span class="badge {{ $emergencyClasses[$request->emergency_level] ?? 'badge-gray' }}">
                                {{ ucfirst($request->emergency_level ?? 'normal') }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('dashboard.requests.show', $request->id) }}" class="btn-outline btn-sm">
                                    View
                                </a>
                                @if($request->status === 'pending')
                                    <a href="{{ route('dashboard.requests.edit', $request->id) }}" class="btn-secondary btn-sm">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('dashboard.requests.cancel', $request->id) }}"
                                          x-data="{}"
                                          @submit.prevent="if(confirm('Cancel this request?')) $el.submit()">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-danger btn-sm">
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($requests->hasPages())
            <div class="mt-5 pt-4 border-t border-gray-100">
                {{ $requests->withQueryString()->links() }}
            </div>
        @endif

    @else
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mb-4">
                <i data-lucide="droplet" class="w-8 h-8 text-red-200"></i>
            </div>
            <h3 class="text-base font-semibold text-gray-700 mb-1">No requests found</h3>
            <p class="text-sm text-gray-400 mb-5">
                @if(request()->hasAny(['search','status','blood_group']))
                    No requests match your current filters.
                @else
                    You haven't submitted any blood requests yet.
                @endif
            </p>
            @if(request()->hasAny(['search','status','blood_group']))
                <a href="{{ route('dashboard.requests.index') }}" class="btn-outline btn-sm">Clear Filters</a>
            @else
                <a href="{{ route('dashboard.requests.create') }}" class="btn-primary">Create First Request</a>
            @endif
        </div>
    @endif
</div>

@endsection
