@extends('layouts.dashboard')

@section('title', 'Request #' . $bloodRequest->id)

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="{{ route('dashboard.index') }}" class="hover:text-red-600 transition-colors">Dashboard</a>
    <i data-lucide="chevron-right" class="w-4 h-4"></i>
    <a href="{{ route('dashboard.requests.index') }}" class="hover:text-red-600 transition-colors">My Requests</a>
    <i data-lucide="chevron-right" class="w-4 h-4"></i>
    <span class="text-gray-900 font-medium">Request #{{ $bloodRequest->id }}</span>
</nav>

{{-- Status Banner --}}
@php
    $bannerConfig = [
        'pending'     => ['bg' => 'bg-yellow-50 border-yellow-300', 'text' => 'text-yellow-800', 'icon' => 'clock', 'iconColor' => 'text-yellow-500'],
        'approved'    => ['bg' => 'bg-blue-50 border-blue-300',   'text' => 'text-blue-800',   'icon' => 'check-circle', 'iconColor' => 'text-blue-500'],
        'in_progress' => ['bg' => 'bg-purple-50 border-purple-300','text' => 'text-purple-800','icon' => 'loader',       'iconColor' => 'text-purple-500'],
        'fulfilled'   => ['bg' => 'bg-green-50 border-green-300', 'text' => 'text-green-800', 'icon' => 'heart-handshake','iconColor' => 'text-green-500'],
        'cancelled'   => ['bg' => 'bg-gray-50 border-gray-300',   'text' => 'text-gray-700',  'icon' => 'x-circle',     'iconColor' => 'text-gray-400'],
        'rejected'    => ['bg' => 'bg-red-50 border-red-300',     'text' => 'text-red-800',   'icon' => 'ban',          'iconColor' => 'text-red-500'],
    ];
    $bc = $bannerConfig[$bloodRequest->status] ?? $bannerConfig['pending'];
    $statusClasses = [
        'pending'     => 'badge-yellow', 'approved'    => 'badge-blue',
        'in_progress' => 'badge-purple', 'fulfilled'   => 'badge-green',
        'cancelled'   => 'badge-gray',   'rejected'    => 'badge-red',
    ];
    $emergencyClasses = ['normal' => 'badge-gray', 'urgent' => 'badge-orange', 'critical' => 'badge-red'];
@endphp

<div class="border rounded-xl p-4 mb-6 flex items-center gap-3 {{ $bc['bg'] }}">
    <i data-lucide="{{ $bc['icon'] }}" class="w-5 h-5 flex-shrink-0 {{ $bc['iconColor'] }}"></i>
    <div class="flex-1">
        <p class="text-sm font-semibold {{ $bc['text'] }}">
            Status: {{ ucfirst(str_replace('_', ' ', $bloodRequest->status)) }}
        </p>
        <p class="text-xs {{ $bc['text'] }} opacity-80 mt-0.5">
            @if($bloodRequest->status === 'pending') Your request is awaiting admin review.
            @elseif($bloodRequest->status === 'approved') Great! Your request has been approved and is visible to donors.
            @elseif($bloodRequest->status === 'in_progress') A donor is in contact — blood collection is in progress.
            @elseif($bloodRequest->status === 'fulfilled') This request has been fulfilled. Thank you!
            @elseif($bloodRequest->status === 'cancelled') This request has been cancelled.
            @elseif($bloodRequest->status === 'rejected') This request was rejected. Contact support for details.
            @endif
        </p>
    </div>
    <span class="badge {{ $statusClasses[$bloodRequest->status] ?? 'badge-gray' }} text-sm px-3 py-1">
        {{ ucfirst(str_replace('_', ' ', $bloodRequest->status)) }}
    </span>
</div>

{{-- Main Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Request Details --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Patient Details --}}
        <div class="card">
            <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4 text-red-500"></i>
                Patient Information
            </h2>
            <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Patient Name</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $bloodRequest->patient_name }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Blood Group</p>
                    <span class="blood-group-badge blood-group-badge-lg">{{ $bloodRequest->blood_group }}</span>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Bags Required</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $bloodRequest->bags_required }} bag(s)</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Emergency Level</p>
                    <span class="badge {{ $emergencyClasses[$bloodRequest->emergency_level] ?? 'badge-gray' }}">
                        {{ ucfirst($bloodRequest->emergency_level ?? 'Normal') }}
                    </span>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Required Date</p>
                    <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($bloodRequest->required_date)->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Contact Number</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $bloodRequest->contact_number }}</p>
                </div>
            </div>
        </div>

        {{-- Hospital Details --}}
        <div class="card">
            <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="building-2" class="w-4 h-4 text-red-500"></i>
                Hospital Information
            </h2>
            <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                <div class="col-span-2">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Hospital Name</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $bloodRequest->hospital_name }}</p>
                </div>
                @if($bloodRequest->hospital_address)
                <div class="col-span-2">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Address</p>
                    <p class="text-sm text-gray-700">{{ $bloodRequest->hospital_address }}</p>
                </div>
                @endif
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">District</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $bloodRequest->district }}</p>
                </div>
                @if($bloodRequest->area)
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Area / Thana</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $bloodRequest->area }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Additional Info --}}
        @if($bloodRequest->additional_info)
        <div class="card">
            <h2 class="text-base font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-red-500"></i>
                Additional Information
            </h2>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $bloodRequest->additional_info }}</p>
        </div>
        @endif

    </div>

    {{-- RIGHT: Actions & Timeline --}}
    <div class="space-y-6" x-data="{ showCancelModal: false }">

        {{-- Request Meta --}}
        <div class="card">
            <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-gray-400"></i>
                Request Info
            </h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Request ID</span>
                    <span class="font-mono font-semibold text-gray-800">#{{ $bloodRequest->id }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Submitted</span>
                    <span class="text-gray-700">{{ $bloodRequest->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Last Updated</span>
                    <span class="text-gray-700">{{ $bloodRequest->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        @if($bloodRequest->status === 'pending')
        <div class="card">
            <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <i data-lucide="zap" class="w-4 h-4 text-gray-400"></i>
                Actions
            </h2>
            <div class="space-y-2">
                <a href="{{ route('dashboard.requests.edit', $bloodRequest->id) }}" class="btn-secondary w-full flex items-center justify-center gap-2">
                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                    Edit Request
                </a>
                <button @click="showCancelModal = true" class="btn-danger w-full flex items-center justify-center gap-2">
                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                    Cancel Request
                </button>
            </div>
        </div>
        @endif

        {{-- Status Timeline --}}
        <div class="card">
            <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i data-lucide="git-branch" class="w-4 h-4 text-gray-400"></i>
                Status Timeline
            </h2>
            @php
                $allStatuses = ['pending', 'approved', 'in_progress', 'fulfilled'];
                $currentIndex = array_search($bloodRequest->status, $allStatuses);
                if($bloodRequest->status === 'cancelled' || $bloodRequest->status === 'rejected') {
                    $currentIndex = -1;
                }
            @endphp
            <div class="space-y-3">
                @foreach($allStatuses as $i => $st)
                @php
                    $isCompleted = $currentIndex !== false && $i <= $currentIndex;
                    $isCurrent = $currentIndex !== false && $i === $currentIndex;
                @endphp
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-full border-2 flex items-center justify-center flex-shrink-0
                        {{ $isCompleted ? 'bg-green-500 border-green-500' : 'border-gray-200 bg-white' }}">
                        @if($isCompleted)
                            <i data-lucide="check" class="w-3.5 h-3.5 text-white"></i>
                        @else
                            <span class="w-2 h-2 rounded-full {{ $isCurrent ? 'bg-blue-400' : 'bg-gray-200' }}"></span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold {{ $isCompleted ? 'text-gray-800' : 'text-gray-400' }}">
                            {{ ucfirst(str_replace('_', ' ', $st)) }}
                        </p>
                    </div>
                    @if($isCurrent)
                        <span class="ml-auto badge badge-blue text-xs">Current</span>
                    @endif
                </div>
                @if(!$loop->last)
                    <div class="ml-3.5 w-px h-3 bg-gray-200"></div>
                @endif
                @endforeach

                @if($bloodRequest->status === 'cancelled')
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full border-2 bg-gray-200 border-gray-300 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="x" class="w-3.5 h-3.5 text-gray-400"></i>
                        </div>
                        <p class="text-xs font-semibold text-gray-500">Cancelled</p>
                        <span class="ml-auto badge badge-gray text-xs">Final</span>
                    </div>
                @elseif($bloodRequest->status === 'rejected')
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full border-2 bg-red-100 border-red-300 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="ban" class="w-3.5 h-3.5 text-red-500"></i>
                        </div>
                        <p class="text-xs font-semibold text-red-600">Rejected</p>
                        <span class="ml-auto badge badge-red text-xs">Final</span>
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>

{{-- Cancel Modal --}}
<div x-show="showCancelModal" x-transition.opacity style="display: none;"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
     @click.self="showCancelModal = false">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6" @click.stop>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900">Cancel Request?</h3>
                <p class="text-xs text-gray-500">This action cannot be undone.</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 mb-5">
            Are you sure you want to cancel blood request #{{ $bloodRequest->id }} for <strong>{{ $bloodRequest->patient_name }}</strong>?
        </p>
        <div class="flex gap-3">
            <button @click="showCancelModal = false" class="btn-outline flex-1">Keep Request</button>
            <form method="POST" action="{{ route('dashboard.requests.cancel', $bloodRequest->id) }}" class="flex-1">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-danger w-full">Yes, Cancel</button>
            </form>
        </div>
    </div>
</div>

@endsection
