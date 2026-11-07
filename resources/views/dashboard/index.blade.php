@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')

{{-- Welcome Header --}}
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
            Welcome back, {{ $user->name }}! 👋
        </h1>
        <p class="mt-1 text-gray-500 text-sm">Here's what's happening with your blood requests.</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold
            @if($user->role === 'admin') bg-purple-100 text-purple-700 border border-purple-200
            @elseif($user->role === 'donor') bg-green-100 text-green-700 border border-green-200
            @else bg-blue-100 text-blue-700 border border-blue-200 @endif">
            @if($user->role === 'admin')<i data-lucide="shield" class="w-3.5 h-3.5"></i>
            @elseif($user->role === 'donor')<i data-lucide="heart" class="w-3.5 h-3.5"></i>
            @else<i data-lucide="user" class="w-3.5 h-3.5"></i>@endif
            {{ ucfirst($user->role ?? 'User') }}
        </span>
        <a href="{{ route('dashboard.requests.create') }}" class="btn-primary btn-sm flex items-center gap-1.5">
            <i data-lucide="plus" class="w-4 h-4"></i>
            New Request
        </a>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8">

    {{-- Pending --}}
    <div class="stat-card border-l-4 border-yellow-400">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label text-yellow-600">Pending Requests</p>
                <p class="stat-number text-yellow-700">{{ $pendingRequests }}</p>
                <p class="text-xs text-gray-400 mt-1">Awaiting approval</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0">
                <i data-lucide="clock" class="w-6 h-6 text-yellow-500"></i>
            </div>
        </div>
    </div>

    {{-- Approved --}}
    <div class="stat-card border-l-4 border-blue-400">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label text-blue-600">Approved Requests</p>
                <p class="stat-number text-blue-700">{{ $approvedRequests }}</p>
                <p class="text-xs text-gray-400 mt-1">Currently active</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                <i data-lucide="check-circle" class="w-6 h-6 text-blue-500"></i>
            </div>
        </div>
    </div>

    {{-- Fulfilled --}}
    <div class="stat-card border-l-4 border-green-400">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label text-green-600">Fulfilled Requests</p>
                <p class="stat-number text-green-700">{{ $fulfilledRequests }}</p>
                <p class="text-xs text-gray-400 mt-1">Successfully completed</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                <i data-lucide="heart-handshake" class="w-6 h-6 text-green-500"></i>
            </div>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    {{-- My Recent Requests --}}
    <div class="xl:col-span-2 card">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                <i data-lucide="droplet" class="w-4 h-4 text-red-500"></i>
                My Recent Requests
            </h2>
            <a href="{{ route('dashboard.requests.index') }}" class="text-xs text-red-600 hover:text-red-700 font-medium flex items-center gap-1 hover:underline">
                View all <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </a>
        </div>

        @if($myRequests && $myRequests->count() > 0)
            <div class="table-container">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Blood Group</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myRequests as $request)
                        <tr>
                            <td>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $request->patient_name }}</p>
                                    @if($request->emergency_level === 'critical')
                                        <span class="badge badge-red text-xs">Critical</span>
                                    @elseif($request->emergency_level === 'urgent')
                                        <span class="badge badge-orange text-xs">Urgent</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="blood-group-badge">{{ $request->blood_group }}</span>
                            </td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'pending'     => 'badge-yellow',
                                        'approved'    => 'badge-blue',
                                        'in_progress' => 'badge-purple',
                                        'fulfilled'   => 'badge-green',
                                        'cancelled'   => 'badge-gray',
                                        'rejected'    => 'badge-red',
                                    ];
                                    $sc = $statusClasses[$request->status] ?? 'badge-gray';
                                @endphp
                                <span class="badge {{ $sc }}">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</span>
                            </td>
                            <td class="text-sm text-gray-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($request->required_date)->format('d M Y') }}
                            </td>
                            <td>
                                <a href="{{ route('dashboard.requests.show', $request->id) }}" class="btn-outline btn-sm">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mb-3">
                    <i data-lucide="droplet" class="w-7 h-7 text-red-300"></i>
                </div>
                <p class="text-sm font-medium text-gray-500">No requests yet</p>
                <p class="text-xs text-gray-400 mt-1 mb-4">Create your first blood request to get started.</p>
                <a href="{{ route('dashboard.requests.create') }}" class="btn-primary btn-sm">New Request</a>
            </div>
        @endif
    </div>

    {{-- Recent Notifications --}}
    <div class="card">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                <i data-lucide="bell" class="w-4 h-4 text-red-500"></i>
                Notifications
                @if($unreadCount > 0)
                    <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 bg-red-600 text-white text-xs font-bold rounded-full">{{ $unreadCount }}</span>
                @endif
            </h2>
            <a href="{{ route('dashboard.notifications.index') }}" class="text-xs text-red-600 hover:text-red-700 font-medium hover:underline flex items-center gap-1">
                All <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </a>
        </div>

        @if($notifications && $notifications->count() > 0)
            <div class="space-y-3">
                @foreach($notifications as $notification)
                <a href="{{ $notification->data['url'] ?? '#' }}"
                   class="block p-3 rounded-lg border {{ $notification->read_at ? 'border-gray-100 bg-gray-50' : 'border-red-100 bg-red-50' }} hover:shadow-sm transition-shadow">
                    <div class="flex items-start gap-2">
                        <div class="w-7 h-7 rounded-full {{ $notification->read_at ? 'bg-gray-200' : 'bg-red-100' }} flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-lucide="bell" class="w-3.5 h-3.5 {{ $notification->read_at ? 'text-gray-400' : 'text-red-500' }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800 leading-tight">{{ $notification->data['title'] ?? 'Notification' }}</p>
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$notification->read_at)
                            <span class="w-2 h-2 bg-red-500 rounded-full flex-shrink-0 mt-1.5"></span>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                    <i data-lucide="bell-off" class="w-6 h-6 text-gray-300"></i>
                </div>
                <p class="text-sm text-gray-400">No notifications</p>
            </div>
        @endif
    </div>

</div>

{{-- Donation History (if donor) --}}
@if($donationHistory !== null)
<div class="card mb-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2">
            <i data-lucide="heart" class="w-4 h-4 text-red-500"></i>
            My Donation History
        </h2>
        <a href="{{ route('dashboard.donor-profile') }}" class="text-xs text-red-600 hover:text-red-700 font-medium hover:underline flex items-center gap-1">
            Donor Profile <i data-lucide="arrow-right" class="w-3 h-3"></i>
        </a>
    </div>

    @if($donationHistory->count() > 0)
        <div class="table-container">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Hospital</th>
                        <th>Blood Group</th>
                        <th>Bags</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donationHistory as $donation)
                    <tr>
                        <td class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($donation->donated_at)->format('d M Y') }}</td>
                        <td class="text-sm text-gray-700">{{ $donation->hospital_name }}</td>
                        <td><span class="blood-group-badge">{{ $donation->blood_group }}</span></td>
                        <td class="text-sm text-gray-700">{{ $donation->bags_donated }} bag(s)</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-10 text-center">
            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mb-3">
                <i data-lucide="heart" class="w-6 h-6 text-red-200"></i>
            </div>
            <p class="text-sm font-medium text-gray-500">No donations recorded yet</p>
            <p class="text-xs text-gray-400 mt-1">Your donation history will appear here.</p>
        </div>
    @endif
</div>
@endif

{{-- Quick Actions --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

    <a href="{{ route('dashboard.requests.create') }}" class="card card-hover flex items-center gap-4 group p-5">
        <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0 group-hover:bg-red-600 transition-colors">
            <i data-lucide="plus-circle" class="w-6 h-6 text-red-600 group-hover:text-white transition-colors"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800 group-hover:text-red-600 transition-colors">New Blood Request</p>
            <p class="text-xs text-gray-400 mt-0.5">Request blood for a patient</p>
        </div>
        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300 ml-auto group-hover:text-red-500 transition-colors"></i>
    </a>

    <a href="{{ route('donors.index') }}" class="card card-hover flex items-center gap-4 group p-5">
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 transition-colors">
            <i data-lucide="users" class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">Find Donors</p>
            <p class="text-xs text-gray-400 mt-0.5">Search available blood donors</p>
        </div>
        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300 ml-auto group-hover:text-blue-500 transition-colors"></i>
    </a>

    <a href="{{ route('dashboard.profile.edit') }}" class="card card-hover flex items-center gap-4 group p-5">
        <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0 group-hover:bg-purple-600 transition-colors">
            <i data-lucide="user-cog" class="w-6 h-6 text-purple-600 group-hover:text-white transition-colors"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800 group-hover:text-purple-600 transition-colors">Update Profile</p>
            <p class="text-xs text-gray-400 mt-0.5">Manage your account settings</p>
        </div>
        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300 ml-auto group-hover:text-purple-500 transition-colors"></i>
    </a>

</div>

@endsection
