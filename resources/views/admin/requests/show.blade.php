@extends('layouts.admin')

@section('title', 'Blood Request #' . $bloodRequest->id)

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">Dashboard</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
    <a href="{{ route('admin.requests.index') }}" class="hover:text-red-600">Blood Requests</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
    <span class="text-red-600 font-medium">Request #{{ $bloodRequest->id }}</span>
@endsection

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            Blood Request #{{ $bloodRequest->id }}
            <span class="badge badge-{{ $bloodRequest->status_badge_color }} text-sm uppercase">
                {{ $bloodRequest->status }}
            </span>
        </h1>
        <p class="text-xs text-gray-500 mt-1">Submitted on {{ $bloodRequest->created_at->format('d M, Y h:i A') }}</p>
    </div>

    {{-- Quick Action Buttons --}}
    <div class="flex items-center gap-2">
        @if($bloodRequest->status === 'pending')
            <form method="POST" action="{{ route('admin.requests.approve', $bloodRequest) }}" class="inline">
                @csrf
                <button type="submit" class="btn-success text-sm py-2 px-4 shadow-sm">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Approve Request
                </button>
            </form>
        @endif

        <form method="POST" action="{{ route('admin.requests.destroy', $bloodRequest) }}"
              onsubmit="return confirm('Permanently delete this blood request?')" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger text-sm py-2 px-3 shadow-sm" title="Delete">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    {{-- Left Column: Request Details (8 cols) --}}
    <div class="lg:col-span-8 space-y-6">

        {{-- Details Card --}}
        <div class="card p-6 md:p-8">
            <div class="flex items-center gap-4 pb-6 mb-6 border-b border-gray-100">
                <div class="blood-group-badge-lg">
                    {{ $bloodRequest->blood_group }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $bloodRequest->patient_name }}</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Emergency Level:
                        <span class="font-semibold text-gray-800 uppercase">{{ $bloodRequest->emergency_level }}</span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                <div>
                    <span class="text-xs uppercase text-gray-400 font-semibold tracking-wider">Hospital / Clinic</span>
                    <p class="font-bold text-gray-900 mt-0.5">{{ $bloodRequest->hospital_name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $bloodRequest->hospital_address }}</p>
                </div>

                <div>
                    <span class="text-xs uppercase text-gray-400 font-semibold tracking-wider">Location</span>
                    <p class="font-bold text-gray-900 mt-0.5">{{ $bloodRequest->district }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $bloodRequest->area ?: 'Area not specified' }}</p>
                </div>

                <div>
                    <span class="text-xs uppercase text-gray-400 font-semibold tracking-wider">Required Date & Bags</span>
                    <p class="font-bold text-blood-700 mt-0.5">
                        {{ $bloodRequest->required_date->format('d M, Y') }} ({{ $bloodRequest->bags_needed }} Bag{{ $bloodRequest->bags_needed > 1 ? 's' : '' }})
                    </p>
                </div>

                <div>
                    <span class="text-xs uppercase text-gray-400 font-semibold tracking-wider">Requester Contact</span>
                    <p class="font-mono font-bold text-gray-900 mt-0.5">{{ $bloodRequest->contact_number }}</p>
                </div>
            </div>

            @if($bloodRequest->notes)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <span class="text-xs uppercase text-gray-400 font-semibold tracking-wider">Requester Notes</span>
                    <div class="bg-gray-50 p-4 rounded-xl text-xs text-gray-700 mt-1 leading-relaxed border border-gray-200">
                        {{ $bloodRequest->notes }}
                    </div>
                </div>
            @endif

            @if($bloodRequest->rejection_reason)
                <div class="mt-6 pt-6 border-t border-red-100">
                    <span class="text-xs uppercase text-red-500 font-semibold tracking-wider">Rejection Reason</span>
                    <div class="bg-red-50 p-4 rounded-xl text-xs text-red-700 mt-1 leading-relaxed border border-red-200">
                        {{ $bloodRequest->rejection_reason }}
                    </div>
                </div>
            @endif
        </div>

        {{-- Matching Available Donors --}}
        <div class="card p-6 md:p-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-green-600"></i>
                    Matching Available Donors in {{ $bloodRequest->district }} ({{ $matchingDonors->count() }})
                </h3>
            </div>

            @if($matchingDonors->count() > 0)
                <div class="space-y-3">
                    @foreach($matchingDonors as $donor)
                        <div class="p-3.5 bg-gray-50 rounded-xl flex items-center justify-between border border-gray-100">
                            <div class="flex items-center gap-3">
                                <span class="blood-group-badge w-8 h-8 text-xs font-bold">{{ $donor->blood_group }}</span>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $donor->user->name ?? 'Donor' }}</h4>
                                    <p class="text-xs text-gray-400">
                                        {{ $donor->area ?: $donor->district }} • Phone: <span class="font-mono text-gray-700">{{ $donor->phone }}</span>
                                    </p>
                                </div>
                            </div>
                            <span class="badge badge-green text-xs">Available</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-gray-400">No active donors found in this district with blood group {{ $bloodRequest->blood_group }}.</p>
            @endif
        </div>
    </div>

    {{-- Right Column: Admin Management Controls (4 cols) --}}
    <div class="lg:col-span-4 space-y-6">

        {{-- Status Update Form --}}
        <div class="card p-6">
            <h3 class="text-sm font-bold text-gray-900 mb-3">Update Status</h3>
            <form method="POST" action="{{ route('admin.requests.status', $bloodRequest) }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="status" class="label text-xs">Current State</label>
                    <select id="status" name="status" class="select text-sm py-2" required>
                        <option value="pending" {{ $bloodRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $bloodRequest->status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="in_progress" {{ $bloodRequest->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="fulfilled" {{ $bloodRequest->status === 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                        <option value="cancelled" {{ $bloodRequest->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="rejected" {{ $bloodRequest->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary w-full justify-center text-sm py-2">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Apply Status Change
                </button>
            </form>
        </div>

        {{-- Reject Request Form if Pending --}}
        @if(in_array($bloodRequest->status, ['pending', 'approved']))
            <div class="card p-6 border border-red-200">
                <h3 class="text-sm font-bold text-red-700 mb-2">Reject Request</h3>
                <p class="text-xs text-gray-500 mb-3">Provide a clear explanation for the requester.</p>

                <form method="POST" action="{{ route('admin.requests.reject', $bloodRequest) }}" class="space-y-3">
                    @csrf

                    <div>
                        <textarea name="rejection_reason" rows="3" class="input text-xs"
                                  placeholder="Reason for rejection (e.g. invalid phone number, already fulfilled)..." required></textarea>
                    </div>

                    <button type="submit" class="btn-danger w-full justify-center text-xs py-2">
                        <i data-lucide="x" class="w-4 h-4"></i> Confirm Rejection
                    </button>
                </form>
            </div>
        @endif

        {{-- Requester Info --}}
        <div class="card p-6">
            <h3 class="text-xs uppercase font-semibold text-gray-400 tracking-wider mb-3">Requester Account</h3>
            <div class="space-y-2 text-xs">
                <p class="font-bold text-gray-900 text-sm">{{ $bloodRequest->user->name }}</p>
                <p class="text-gray-500">{{ $bloodRequest->user->email }}</p>
                <p class="text-gray-500">Phone: {{ $bloodRequest->user->phone ?: 'Not provided' }}</p>
                <div class="pt-2">
                    <a href="{{ route('admin.users.show', $bloodRequest->user) }}" class="text-blood-700 font-semibold hover:underline">
                        View User Profile →
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
