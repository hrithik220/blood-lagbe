@extends('layouts.admin')

@section('title', 'User: ' . $user->name)

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition-colors">Dashboard</a>
    <i data-lucide="chevron-right" class="w-3 h-3"></i>
    <a href="{{ route('admin.users.index') }}" class="hover:text-red-600 transition-colors">Users</a>
    <i data-lucide="chevron-right" class="w-3 h-3"></i>
    <span class="text-red-600 font-medium">{{ $user->name }}</span>
@endsection

@section('content')

{{-- Action Buttons --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <a href="{{ route('admin.users.index') }}" class="btn-outline btn-sm">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Users
    </a>
    <div class="flex items-center gap-2" x-data="{ deleteModal: false }">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn-outline btn-sm">
            <i data-lucide="pencil" class="w-4 h-4"></i> Edit
        </a>
        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
            @csrf
            <button type="submit"
                class="{{ $user->is_active ? 'btn-danger' : 'btn-success' }} btn-sm">
                <i data-lucide="{{ $user->is_active ? 'user-x' : 'user-check' }}" class="w-4 h-4"></i>
                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
            </button>
        </form>
        <button @click="deleteModal = true" class="btn-danger btn-sm">
            <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
        </button>

        {{-- Delete Modal --}}
        <div x-show="deleteModal" x-transition
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
             style="display:none;">
            <div @click.outside="deleteModal = false" class="card max-w-md w-full p-6 shadow-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i data-lucide="trash-2" class="w-5 h-5 text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Delete User</h3>
                        <p class="text-sm text-gray-500">This action cannot be undone.</p>
                    </div>
                </div>
                <p class="text-sm text-gray-700 mb-6">
                    Are you sure you want to delete <strong>{{ $user->name }}</strong>?
                </p>
                <div class="flex justify-end gap-3">
                    <button @click="deleteModal = false" class="btn-outline btn-sm">Cancel</button>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">
                            <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- User Info Card --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="card p-6">
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-20 h-20 bg-red-700 rounded-full flex items-center justify-center text-white text-3xl font-bold mb-4 shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
                <div class="mt-3 flex items-center gap-2">
                    @if($user->role === 'admin')
                        <span class="badge-red">Admin</span>
                    @elseif($user->role === 'donor')
                        <span class="badge-green">Donor</span>
                    @else
                        <span class="badge-gray">User</span>
                    @endif
                    @if($user->is_active)
                        <span class="badge-green">Active</span>
                    @else
                        <span class="badge-gray">Inactive</span>
                    @endif
                </div>
            </div>
            <div class="space-y-3 border-t border-gray-100 pt-4">
                <div class="flex items-center gap-3 text-sm">
                    <i data-lucide="phone" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                    <span class="text-gray-600">{{ $user->phone ?? 'Not provided' }}</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                    <span class="text-gray-600">{{ $user->district ?? 'Not provided' }}</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <i data-lucide="calendar" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                    <span class="text-gray-600">Joined {{ $user->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Donor Profile --}}
        @if($user->donorProfile)
        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="heart" class="w-4 h-4 text-red-600"></i> Donor Profile
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Blood Group</span>
                    <span class="badge-red">{{ $user->donorProfile->blood_group }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Available</span>
                    @if($user->donorProfile->is_available)
                        <span class="badge-green">Yes</span>
                    @else
                        <span class="badge-gray">No</span>
                    @endif
                </div>
                @if($user->donorProfile->last_donation_date)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Last Donation</span>
                    <span class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($user->donorProfile->last_donation_date)->format('M d, Y') }}</span>
                </div>
                @endif
                @if($user->donorProfile->bio)
                <div class="pt-2 border-t border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">Bio</p>
                    <p class="text-sm text-gray-700">{{ $user->donorProfile->bio }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Blood Requests Table --}}
    <div class="lg:col-span-2">
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900">Blood Requests</h3>
                <span class="badge-gray">{{ $user->bloodRequests->count() ?? 0 }} total</span>
            </div>
            <div class="table-container rounded-none border-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Blood Group</th>
                            <th>Status</th>
                            <th>Required Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($user->bloodRequests ?? [] as $req)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-xs text-gray-400">{{ $req->id }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $req->patient_name }}</td>
                            <td class="px-4 py-3">
                                <span class="badge-red">{{ $req->blood_group }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @php $status = $req->status ?? 'pending'; @endphp
                                @if($status === 'pending')     <span class="badge-yellow">Pending</span>
                                @elseif($status === 'approved') <span class="badge-blue">Approved</span>
                                @elseif($status === 'in_progress') <span class="badge-purple">In Progress</span>
                                @elseif($status === 'fulfilled') <span class="badge-green">Fulfilled</span>
                                @elseif($status === 'cancelled') <span class="badge-gray">Cancelled</span>
                                @elseif($status === 'rejected') <span class="badge-red">Rejected</span>
                                @else <span class="badge-gray">{{ ucfirst($status) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">
                                {{ $req->required_date ? \Carbon\Carbon::parse($req->required_date)->format('M d, Y') : '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-400">
                                <i data-lucide="droplet" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                No blood requests yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
