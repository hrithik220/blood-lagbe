@extends('layouts.app')

@section('title', 'Find Blood Donors - Blood Lagbe')

@section('content')

{{-- ============================================================ --}}
{{-- PAGE HERO --}}
{{-- ============================================================ --}}
<section class="hero-gradient relative overflow-hidden py-16">
    <div class="absolute top-0 right-0 opacity-10" aria-hidden="true">
        <svg width="300" height="390" viewBox="0 0 100 130" fill="white"><path d="M50 5 C50 5 15 50 15 75 C15 97 30.5 115 50 115 C69.5 115 85 97 85 75 C85 50 50 5 50 5Z"/></svg>
    </div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-2xl">
            <div class="flex items-center gap-2 text-red-200 text-sm mb-3">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <span class="text-white">Find Donors</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white mb-3 leading-tight">
                Find Blood <span class="text-red-200">Donors</span>
            </h1>
            <p class="text-red-100 text-lg">
                Search our network of voluntary blood donors across Bangladesh. Filter by blood group, district, and availability.
            </p>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 40L1440 40L1440 0C1200 30 960 40 720 40C480 40 240 30 0 0Z" fill="#f9fafb"/>
        </svg>
    </div>
</section>

{{-- ============================================================ --}}
{{-- FILTER BAR --}}
{{-- ============================================================ --}}
<section class="bg-gray-50 py-6 -mt-1">
    <div class="container mx-auto px-4">
        <div class="card shadow-lg">
            <div class="flex items-center gap-2 mb-4">
                <i data-lucide="filter" class="w-5 h-5 text-red-600"></i>
                <h2 class="font-bold text-gray-800">Filter Donors</h2>
            </div>
            <form method="GET" action="{{ route('donors.index') }}">
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Blood Group --}}
                    <div>
                        <label for="blood_group" class="label">Blood Group</label>
                        <select name="blood_group" id="blood_group" class="select">
                            <option value="">All Blood Groups</option>
                            @foreach($bloodGroups as $bg)
                                <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>
                                    {{ $bg }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- District --}}
                    <div>
                        <label for="district" class="label">District</label>
                        <select name="district" id="district" class="select">
                            <option value="">All Districts</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->district ?? $district }}" {{ request('district') === ($district->district ?? $district) ? 'selected' : '' }}>
                                    {{ $district->district ?? $district }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Area --}}
                    <div>
                        <label for="area" class="label">Area / Upazila</label>
                        <input type="text" name="area" id="area" class="input"
                               placeholder="e.g. Mirpur, Dhanmondi"
                               value="{{ request('area') }}">
                    </div>

                    {{-- Availability --}}
                    <div>
                        <label for="is_available" class="label">Availability</label>
                        <select name="is_available" id="is_available" class="select">
                            <option value="">Any Availability</option>
                            <option value="1" {{ request('is_available') === '1' ? 'selected' : '' }}>Available Now</option>
                            <option value="0" {{ request('is_available') === '0' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 mt-5 pt-4 border-t border-gray-100">
                    <button type="submit" class="btn-primary flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        Search Donors
                    </button>
                    <a href="{{ route('donors.index') }}" class="btn-outline flex items-center gap-2 text-sm">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Clear Filters
                    </a>
                    @if(request()->hasAny(['blood_group','district','area','is_available']))
                        <span class="text-sm text-gray-500">
                            <span class="font-semibold text-red-600">{{ $donors->total() }}</span> donor(s) found
                        </span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- RESULTS GRID --}}
{{-- ============================================================ --}}
<section class="section bg-gray-50 pt-8">
    <div class="container mx-auto px-4">

        @if($donors->isEmpty())
            {{-- Empty State --}}
            <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200 shadow-sm">
                <i data-lucide="user-x" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                <h3 class="text-xl font-bold text-gray-500 mb-2">No Donors Found</h3>
                <p class="text-gray-400 mb-6 max-w-sm mx-auto">
                    No donors match your current filters. Try adjusting your search criteria.
                </p>
                <a href="{{ route('donors.index') }}" class="btn-outline">
                    Clear Filters
                </a>
            </div>
        @else
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-600">
                    Showing <span class="font-semibold text-gray-800">{{ $donors->firstItem() }}–{{ $donors->lastItem() }}</span>
                    of <span class="font-semibold text-red-600">{{ $donors->total() }}</span> donors
                </p>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <i data-lucide="shield-check" class="w-4 h-4 text-green-500"></i>
                    All donors are verified
                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($donors as $donor)
                    <div class="card card-hover group">
                        {{-- Header --}}
                        <div class="flex items-start justify-between mb-5">
                            <div class="blood-group-badge-lg">
                                {{ $donor->blood_group }}
                            </div>
                            @if($donor->is_available)
                                <span class="badge badge-green flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full inline-block animate-pulse"></span>
                                    Available
                                </span>
                            @else
                                <span class="badge badge-gray flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full inline-block"></span>
                                    Unavailable
                                </span>
                            @endif
                        </div>

                        {{-- Donor Name --}}
                        <h3 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-red-700 transition-colors">
                            {{ $donor->user->name ?? 'Anonymous Donor' }}
                        </h3>

                        {{-- Details --}}
                        <div class="space-y-2 text-sm text-gray-600 mb-5">
                            @if($donor->district)
                                <div class="flex items-center gap-2">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-red-400 flex-shrink-0"></i>
                                    <span>{{ $donor->district }}{{ $donor->area ? ', ' . $donor->area : '' }}</span>
                                </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <i data-lucide="calendar-check" class="w-4 h-4 text-red-400 flex-shrink-0"></i>
                                <span>
                                    @if($donor->last_donation_date)
                                        Last donated: {{ \Carbon\Carbon::parse($donor->last_donation_date)->format('d M, Y') }}
                                    @else
                                        No donation record
                                    @endif
                                </span>
                            </div>
                            @if($donor->bio)
                                <div class="flex items-start gap-2">
                                    <i data-lucide="info" class="w-4 h-4 text-red-400 flex-shrink-0 mt-0.5"></i>
                                    <span class="line-clamp-2 text-gray-500 italic">"{{ $donor->bio }}"</span>
                                </div>
                            @endif
                        </div>

                        {{-- Action Button --}}
                        <a href="{{ auth()->check() ? route('dashboard.requests.create') : route('register') }}"
                           class="btn-primary w-full text-center text-sm flex items-center justify-center gap-2 group-hover:shadow-lg transition-shadow">
                            <i data-lucide="droplets" class="w-4 h-4"></i>
                            Request Blood
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($donors->hasPages())
                <div class="mt-10 flex justify-center">
                    {{ $donors->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</section>

{{-- Register CTA --}}
<section class="bg-white py-12 border-t border-gray-100">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-lg mx-auto">
            <i data-lucide="heart-handshake" class="w-12 h-12 text-red-400 mx-auto mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Are you a donor?</h3>
            <p class="text-gray-600 mb-5">Join our network and help save lives in your community.</p>
            <a href="{{ route('donors.register') }}" class="btn-primary inline-flex items-center gap-2">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Register as Donor
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();
    });
</script>
@endpush
