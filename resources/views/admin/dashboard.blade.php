@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="text-red-600 font-medium">Dashboard</span>
@endsection

@section('content')

{{-- ═══════════════════════════════════════════
     STAT CARDS — 8 cards, 4 columns on desktop
═══════════════════════════════════════════ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    {{-- Total Users --}}
    <div class="card p-5 flex items-start gap-4">
        <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
        </div>
        <div class="min-w-0">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_users'] ?? 0) }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">Total Users</p>
            <span class="inline-flex items-center gap-1 text-xs text-blue-600 mt-1">
                <i data-lucide="trending-up" class="w-3 h-3"></i> All registered
            </span>
        </div>
    </div>

    {{-- Total Donors --}}
    <div class="card p-5 flex items-start gap-4">
        <div class="w-11 h-11 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i data-lucide="heart" class="w-5 h-5 text-green-600"></i>
        </div>
        <div class="min-w-0">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_donors'] ?? 0) }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">Total Donors</p>
            <span class="inline-flex items-center gap-1 text-xs text-green-600 mt-1">
                <i data-lucide="trending-up" class="w-3 h-3"></i> Active donors
            </span>
        </div>
    </div>

    {{-- Total Requests --}}
    <div class="card p-5 flex items-start gap-4">
        <div class="w-11 h-11 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i data-lucide="droplet" class="w-5 h-5 text-purple-600"></i>
        </div>
        <div class="min-w-0">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_requests'] ?? 0) }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">Total Requests</p>
            <span class="inline-flex items-center gap-1 text-xs text-purple-600 mt-1">
                <i data-lucide="activity" class="w-3 h-3"></i> All time
            </span>
        </div>
    </div>

    {{-- Pending --}}
    <div class="card p-5 flex items-start gap-4">
        <div class="w-11 h-11 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i data-lucide="clock" class="w-5 h-5 text-yellow-600"></i>
        </div>
        <div class="min-w-0">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending_requests'] ?? 0) }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">Pending</p>
            <span class="inline-flex items-center gap-1 text-xs text-yellow-600 mt-1">
                <i data-lucide="alert-circle" class="w-3 h-3"></i> Needs review
            </span>
        </div>
    </div>

    {{-- Approved --}}
    <div class="card p-5 flex items-start gap-4">
        <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i data-lucide="check-circle" class="w-5 h-5 text-blue-600"></i>
        </div>
        <div class="min-w-0">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['approved_requests'] ?? 0) }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">Approved</p>
            <span class="inline-flex items-center gap-1 text-xs text-blue-600 mt-1">
                <i data-lucide="trending-up" class="w-3 h-3"></i> In progress
            </span>
        </div>
    </div>

    {{-- Fulfilled --}}
    <div class="card p-5 flex items-start gap-4">
        <div class="w-11 h-11 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i data-lucide="shield-check" class="w-5 h-5 text-green-600"></i>
        </div>
        <div class="min-w-0">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['fulfilled_requests'] ?? 0) }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">Fulfilled</p>
            <span class="inline-flex items-center gap-1 text-xs text-green-600 mt-1">
                <i data-lucide="check" class="w-3 h-3"></i> Lives saved
            </span>
        </div>
    </div>

    {{-- Urgent --}}
    <div class="card p-5 flex items-start gap-4">
        <div class="w-11 h-11 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i data-lucide="zap" class="w-5 h-5 text-red-600"></i>
        </div>
        <div class="min-w-0">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['urgent_requests'] ?? 0) }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">Urgent</p>
            <span class="inline-flex items-center gap-1 text-xs text-red-600 mt-1">
                <i data-lucide="alert-triangle" class="w-3 h-3"></i> Immediate need
            </span>
        </div>
    </div>

    {{-- Unread Messages --}}
    <div class="card p-5 flex items-start gap-4">
        <div class="w-11 h-11 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i data-lucide="mail" class="w-5 h-5 text-gray-600"></i>
        </div>
        <div class="min-w-0">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['unread_messages'] ?? 0) }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">Unread Messages</p>
            <span class="inline-flex items-center gap-1 text-xs text-gray-600 mt-1">
                <i data-lucide="inbox" class="w-3 h-3"></i> Contact inbox
            </span>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     CHARTS ROW
═══════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    {{-- Blood Group Distribution --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-gray-900">Blood Group Distribution</h3>
                <p class="text-xs text-gray-500 mt-0.5">Requests by blood group</p>
            </div>
            <span class="badge-red">Donors</span>
        </div>
        @php
            $bloodGroups = $bloodGroupStats ?? [];
            $maxBg = max(array_values($bloodGroups) ?: [1]);
        @endphp
        <div class="space-y-3">
            @foreach($bloodGroups as $group => $count)
            @php $pct = $maxBg > 0 ? round(($count / $maxBg) * 100) : 0; @endphp
            <div class="flex items-center gap-3">
                <span class="w-9 text-xs font-bold text-red-700 text-right flex-shrink-0">{{ $group }}</span>
                <div class="flex-1 bg-gray-100 rounded-full h-6 overflow-hidden">
                    <div class="h-6 bg-gradient-to-r from-red-600 to-red-400 rounded-full flex items-center justify-end pr-2 transition-all duration-500"
                         style="width: {{ max($pct, 4) }}%">
                        <span class="text-xs font-semibold text-white">{{ $count }}</span>
                    </div>
                </div>
            </div>
            @endforeach
            @if(empty($bloodGroups))
            <p class="text-gray-400 text-sm text-center py-6">No data available.</p>
            @endif
        </div>
    </div>

    {{-- Monthly Requests Chart --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-gray-900">Monthly Requests</h3>
                <p class="text-xs text-gray-500 mt-0.5">Request trend this year</p>
            </div>
            <span class="badge-purple">12 months</span>
        </div>
        <canvas id="monthlyChart" height="200"></canvas>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     TABLES ROW
═══════════════════════════════════════════ --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- Pending Requests --}}
    <div class="card">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Pending Requests</h3>
            <a href="{{ route('admin.requests.index', ['status' => 'pending']) }}" class="text-xs text-red-600 hover:underline font-medium">View all →</a>
        </div>
        <div class="table-container rounded-none border-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Group</th>
                        <th>District</th>
                        <th>Emergency</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentRequests ?? [] as $req)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-xs text-gray-400">{{ $req->id }}</td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-900">{{ $req->patient_name }}</p>
                            @if($req->user)
                            <p class="text-xs text-gray-400">by {{ $req->user->name }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge-red text-xs">{{ $req->blood_group }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">{{ $req->district }}</td>
                        <td class="px-4 py-3">
                            @if(($req->emergency_level ?? '') === 'critical')
                                <span class="badge-red">Critical</span>
                            @elseif(($req->emergency_level ?? '') === 'urgent')
                                <span class="badge-orange">Urgent</span>
                            @else
                                <span class="badge-gray">Normal</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <form method="POST" action="{{ route('admin.requests.approve', $req) }}">
                                    @csrf
                                    <button class="btn-success btn-sm text-xs py-1 px-2 rounded-lg">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.requests.reject', $req) }}">
                                    @csrf
                                    <button class="btn-danger btn-sm text-xs py-1 px-2 rounded-lg">
                                        <i data-lucide="x" class="w-3 h-3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-400">
                            <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                            No pending requests.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="card">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Recent Users</h3>
            <a href="{{ route('admin.users.index') }}" class="text-xs text-red-600 hover:underline font-medium">View all →</a>
        </div>
        <div class="table-container rounded-none border-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentUsers ?? [] as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-700 font-bold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($user->role === 'admin')
                                <span class="badge-red">Admin</span>
                            @elseif($user->role === 'donor')
                                <span class="badge-green">Donor</span>
                            @else
                                <span class="badge-gray">User</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-400">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Monthly Requests Chart
    const monthlyData = @json($monthlyRequestStats ?? []);
    const labels = monthlyData.map(d => d.month);
    const counts = monthlyData.map(d => d.count);

    const ctx = document.getElementById('monthlyChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Blood Requests',
                    data: counts,
                    backgroundColor: 'rgba(185, 28, 28, 0.8)',
                    borderColor: 'rgb(185, 28, 28)',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} requests`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    lucide.createIcons();
});
</script>
@endpush
