@extends('layouts.app')

@section('title', 'Blood Requests — Blood Lagbe')
@section('description', 'View urgent and verified blood requests across Bangladesh. Find someone who needs your blood group and help save a life today.')

@section('content')
{{-- Page Header --}}
<div class="hero-gradient hero-pattern text-white py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm text-white mb-3">
                    <i data-lucide="activity" class="w-3.5 h-3.5"></i> Active Patient Requests
                </span>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight">Blood Requests</h1>
                <p class="text-red-100 text-sm md:text-base mt-2 max-w-xl">
                    Every second matters. Browse verified requests from hospitals across Bangladesh and step forward to donate.
                </p>
            </div>
            <div>
                <a href="{{ auth()->check() ? route('dashboard.requests.create') : route('register') }}" class="btn-secondary whitespace-nowrap shadow-xl">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i> Post Blood Request
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Filter Card --}}
    <div class="card p-6 mb-8 border border-gray-200 shadow-sm bg-white">
        <form method="GET" action="{{ route('requests.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label for="blood_group" class="label">Blood Group</label>
                <select id="blood_group" name="blood_group" class="select text-sm py-2.5">
                    <option value="">All Blood Groups</option>
                    @foreach($bloodGroups as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="district" class="label">District</label>
                <input type="text" id="district" name="district" value="{{ request('district') }}"
                       placeholder="e.g. Dhaka, Chittagong" class="input text-sm py-2.5">
            </div>

            <div>
                <label for="emergency_level" class="label">Urgency</label>
                <select id="emergency_level" name="emergency_level" class="select text-sm py-2.5">
                    <option value="">All Urgency Levels</option>
                    <option value="critical" {{ request('emergency_level') === 'critical' ? 'selected' : '' }}>Critical</option>
                    <option value="urgent" {{ request('emergency_level') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="normal" {{ request('emergency_level') === 'normal' ? 'selected' : '' }}>Normal</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn-primary w-full justify-center py-2.5 text-sm">
                    <i data-lucide="filter" class="w-4 h-4"></i> Filter
                </button>
            </div>

            <div>
                @if(request()->hasAny(['blood_group', 'district', 'emergency_level']))
                    <a href="{{ route('requests.index') }}" class="btn-outline w-full justify-center py-2.5 text-sm text-center">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Requests Grid / List --}}
    @if($requests->count() > 0)
        <div class="space-y-4">
            @foreach($requests as $request)
                @php
                    $isCritical = $request->emergency_level === 'critical';
                    $isUrgent = $request->emergency_level === 'urgent';
                @endphp
                <div class="card p-6 hover:shadow-md transition-all border-l-4 {{ $isCritical ? 'border-red-600 bg-red-50/20' : ($isUrgent ? 'border-amber-500 bg-amber-50/20' : 'border-gray-200') }}">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">

                        {{-- Left: Blood Group + Details --}}
                        <div class="flex items-start gap-4 sm:gap-6">
                            <div class="blood-group-badge-lg flex-shrink-0 {{ $isCritical ? 'animate-pulse' : '' }}">
                                {{ $request->blood_group }}
                            </div>

                            <div class="space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-lg font-bold text-gray-900">
                                        {{ Str::limit($request->patient_name, 2, '***') }}
                                    </h3>
                                    @if($isCritical)
                                        <span class="badge badge-red flex items-center gap-1">
                                            <i data-lucide="alert-octagon" class="w-3 h-3"></i> Critical Emergency
                                        </span>
                                    @elseif($isUrgent)
                                        <span class="badge badge-orange flex items-center gap-1">
                                            <i data-lucide="alert-triangle" class="w-3 h-3"></i> Urgent
                                        </span>
                                    @else
                                        <span class="badge badge-blue">Normal</span>
                                    @endif

                                    <span class="badge badge-green">{{ $request->bags_needed }} Bag(s)</span>
                                </div>

                                <p class="text-sm font-semibold text-gray-800 flex items-center gap-1.5">
                                    <i data-lucide="hospital" class="w-4 h-4 text-blood-600"></i>
                                    {{ $request->hospital_name }}
                                </p>

                                <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400"></i>
                                        {{ $request->district }}{{ $request->area ? ', ' . $request->area : '' }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                                        Needed: <strong class="text-gray-700">{{ $request->required_date->format('d M, Y') }}</strong>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-400"></i>
                                        Posted {{ $request->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Right: View Details CTA --}}
                        <div class="flex items-center gap-3 self-end md:self-center">
                            <a href="{{ route('requests.show', $request) }}" class="btn-primary text-sm py-2.5 px-5">
                                View Details <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $requests->links() }}
        </div>
    @else
        <div class="card p-12 text-center max-w-lg mx-auto">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <i data-lucide="check-circle" class="w-8 h-8 text-green-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">No Active Requests Found</h3>
            <p class="text-gray-500 text-sm mb-6">There are currently no active blood requests matching your filter criteria.</p>
            @if(request()->hasAny(['blood_group', 'district', 'emergency_level']))
                <a href="{{ route('requests.index') }}" class="btn-outline text-sm">Clear Filters</a>
            @else
                <a href="{{ auth()->check() ? route('dashboard.requests.create') : route('register') }}" class="btn-primary text-sm">
                    Post a Blood Request
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
