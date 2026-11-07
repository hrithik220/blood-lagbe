@extends('layouts.app')

@section('title', 'Blood Lagbe - রক্তের প্রয়োজন? আমরা আছি।')

@section('content')

{{-- ============================================================ --}}
{{-- HERO SECTION --}}
{{-- ============================================================ --}}
<section class="hero-gradient hero-pattern relative overflow-hidden min-h-screen flex items-center">
    {{-- Animated blood drop decoration --}}
    <div class="absolute top-10 right-10 opacity-20 animate-pulse" aria-hidden="true">
        <svg width="200" height="260" viewBox="0 0 100 130" fill="white" xmlns="http://www.w3.org/2000/svg">
            <path d="M50 5 C50 5 15 50 15 75 C15 97 30.5 115 50 115 C69.5 115 85 97 85 75 C85 50 50 5 50 5Z"/>
        </svg>
    </div>
    <div class="absolute bottom-20 left-10 opacity-10 animate-bounce" aria-hidden="true">
        <svg width="120" height="156" viewBox="0 0 100 130" fill="white" xmlns="http://www.w3.org/2000/svg">
            <path d="M50 5 C50 5 15 50 15 75 C15 97 30.5 115 50 115 C69.5 115 85 97 85 75 C85 50 50 5 50 5Z"/>
        </svg>
    </div>

    <div class="container mx-auto px-4 py-24 relative z-10">
        <div class="max-w-4xl mx-auto text-center text-white">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full px-4 py-2 mb-6">
                <i data-lucide="heart-pulse" class="w-4 h-4 text-red-200"></i>
                <span class="text-sm font-medium text-white">Bangladesh Blood Donation Network</span>
            </div>

            {{-- Main Headline --}}
            <h1 class="text-5xl md:text-7xl font-black mb-4 leading-tight drop-shadow-lg">
                রক্তের প্রয়োজন?<br>
                <span class="text-red-200">আমরা আছি।</span>
            </h1>
            <p class="text-xl md:text-2xl font-light text-red-100 mb-3 tracking-wide">
                Every Drop Counts. Every Donor Matters.
            </p>
            <p class="text-base md:text-lg text-white/80 mb-10 max-w-2xl mx-auto">
                Connect with voluntary blood donors across Bangladesh instantly. Register as a donor or find blood for someone in need — fast, free, and reliable.
            </p>

            {{-- CTA Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                <a href="{{ route('donors.index') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white text-red-700 font-bold px-8 py-4 rounded-xl shadow-xl hover:bg-red-50 hover:scale-105 transition-all duration-200 text-lg">
                    <i data-lucide="search" class="w-5 h-5"></i>
                    Find Donors
                </a>
                <a href="{{ auth()->check() ? route('dashboard.requests.create') : route('register') }}"
                   class="inline-flex items-center justify-center gap-2 bg-transparent border-2 border-white text-white font-bold px-8 py-4 rounded-xl hover:bg-white/20 hover:scale-105 transition-all duration-200 text-lg backdrop-blur-sm">
                    <i data-lucide="droplets" class="w-5 h-5"></i>
                    Request Blood
                </a>
            </div>

            {{-- Floating Stat Pills --}}
            <div class="flex flex-wrap justify-center gap-4">
                <div class="glass-card px-6 py-3 rounded-2xl flex items-center gap-3">
                    <i data-lucide="users" class="w-5 h-5 text-red-200"></i>
                    <div class="text-left">
                        <div class="text-2xl font-black text-white">{{ number_format($stats['total_donors'] ?? 0) }}+</div>
                        <div class="text-xs text-red-200 uppercase tracking-wider">Total Donors</div>
                    </div>
                </div>
                <div class="glass-card px-6 py-3 rounded-2xl flex items-center gap-3">
                    <i data-lucide="activity" class="w-5 h-5 text-red-200"></i>
                    <div class="text-left">
                        <div class="text-2xl font-black text-white">{{ number_format($stats['total_requests'] ?? 0) }}+</div>
                        <div class="text-xs text-red-200 uppercase tracking-wider">Requests Served</div>
                    </div>
                </div>
                <div class="glass-card px-6 py-3 rounded-2xl flex items-center gap-3">
                    <i data-lucide="heart" class="w-5 h-5 text-red-200"></i>
                    <div class="text-left">
                        <div class="text-2xl font-black text-white">{{ number_format($stats['fulfilled_requests'] ?? 0) }}+</div>
                        <div class="text-xs text-red-200 uppercase tracking-wider">Lives Saved</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Wave divider --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 80L60 66.7C120 53 240 27 360 20C480 13 600 27 720 33.3C840 40 960 40 1080 33.3C1200 27 1320 13 1380 6.7L1440 0V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="white"/>
        </svg>
    </div>
</section>

{{-- ============================================================ --}}
{{-- STATS BAR --}}
{{-- ============================================================ --}}
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="stat-card text-center group hover:scale-105 transition-transform duration-200">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-red-100 rounded-2xl mb-4 group-hover:bg-red-600 transition-colors duration-200">
                    <i data-lucide="users" class="w-7 h-7 text-red-600 group-hover:text-white transition-colors duration-200"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['total_donors'] ?? 0) }}</div>
                <div class="stat-label">Registered Donors</div>
            </div>
            <div class="stat-card text-center group hover:scale-105 transition-transform duration-200">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-orange-100 rounded-2xl mb-4 group-hover:bg-orange-500 transition-colors duration-200">
                    <i data-lucide="file-heart" class="w-7 h-7 text-orange-600 group-hover:text-white transition-colors duration-200"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['total_requests'] ?? 0) }}</div>
                <div class="stat-label">Blood Requests</div>
            </div>
            <div class="stat-card text-center group hover:scale-105 transition-transform duration-200">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-green-100 rounded-2xl mb-4 group-hover:bg-green-600 transition-colors duration-200">
                    <i data-lucide="check-circle-2" class="w-7 h-7 text-green-600 group-hover:text-white transition-colors duration-200"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['fulfilled_requests'] ?? 0) }}</div>
                <div class="stat-label">Fulfilled Requests</div>
            </div>
            <div class="stat-card text-center group hover:scale-105 transition-transform duration-200">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-100 rounded-2xl mb-4 group-hover:bg-blue-600 transition-colors duration-200">
                    <i data-lucide="shield-check" class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors duration-200"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['total_users'] ?? 0) }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- HOW IT WORKS --}}
{{-- ============================================================ --}}
<section class="section bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-14">
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Get the blood you need in just three simple steps</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8 relative">
            {{-- Connector line (desktop) --}}
            <div class="hidden md:block absolute top-16 left-1/4 right-1/4 h-0.5 bg-gradient-to-r from-red-200 via-red-400 to-red-200 z-0"></div>

            {{-- Step 1 --}}
            <div class="card text-center relative z-10 hover:shadow-xl transition-shadow duration-200">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-600 text-white rounded-2xl mb-5 mx-auto shadow-lg">
                    <span class="text-2xl font-black">1</span>
                </div>
                <div class="mb-4">
                    <i data-lucide="user-plus" class="w-10 h-10 text-red-500 mx-auto mb-3"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Register</h3>
                <p class="text-gray-600">Create your account and register as a blood donor. It takes less than 2 minutes.</p>
                <a href="{{ route('register') }}" class="btn-primary mt-5 inline-block text-sm">Get Started</a>
            </div>

            {{-- Step 2 --}}
            <div class="card text-center relative z-10 hover:shadow-xl transition-shadow duration-200">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-600 text-white rounded-2xl mb-5 mx-auto shadow-lg">
                    <span class="text-2xl font-black">2</span>
                </div>
                <div class="mb-4">
                    <i data-lucide="file-plus" class="w-10 h-10 text-red-500 mx-auto mb-3"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Post Request</h3>
                <p class="text-gray-600">Submit a blood request with patient details, required blood group, hospital, and urgency level.</p>
                <a href="{{ auth()->check() ? route('dashboard.requests.create') : route('register') }}" class="btn-secondary mt-5 inline-block text-sm">Post Request</a>
            </div>

            {{-- Step 3 --}}
            <div class="card text-center relative z-10 hover:shadow-xl transition-shadow duration-200">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-600 text-white rounded-2xl mb-5 mx-auto shadow-lg">
                    <span class="text-2xl font-black">3</span>
                </div>
                <div class="mb-4">
                    <i data-lucide="handshake" class="w-10 h-10 text-red-500 mx-auto mb-3"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Get Matched</h3>
                <p class="text-gray-600">Our system connects you with available donors in your area. Contact them directly to arrange donation.</p>
                <a href="{{ route('donors.index') }}" class="btn-outline mt-5 inline-block text-sm">Find Donors</a>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- URGENT BLOOD REQUESTS --}}
{{-- ============================================================ --}}
<section class="section bg-white">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="section-title flex items-center gap-2">
                    🚨 Urgent Blood Needed
                </h2>
                <p class="section-subtitle mt-1">These patients urgently need blood donors right now</p>
            </div>
            <a href="{{ route('requests.index') }}" class="btn-outline hidden md:inline-flex items-center gap-2">
                View All <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        @if($urgentRequests->isEmpty())
            <div class="text-center py-16 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <i data-lucide="heart-handshake" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-500 mb-2">No Urgent Requests</h3>
                <p class="text-gray-400">There are no urgent blood requests at the moment. Check back later.</p>
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($urgentRequests as $request)
                    <div class="card card-hover border-l-4 {{ $request->emergency_level === 'critical' ? 'border-l-red-600' : 'border-l-orange-500' }} relative overflow-hidden">
                        @if($request->emergency_level === 'critical')
                            <div class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full m-3 animate-ping"></div>
                        @endif

                        <div class="flex items-start justify-between mb-4">
                            <div class="blood-group-badge-lg">
                                {{ $request->blood_group }}
                            </div>
                            <span class="badge {{ $request->emergency_level === 'critical' ? 'badge-red' : 'badge-orange' }} uppercase font-bold text-xs">
                                {{ $request->emergency_level }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-800 mb-1">
                            {{ \Str::limit(explode(' ', $request->patient_name)[0], 1) }}***
                        </h3>

                        <div class="space-y-2 text-sm text-gray-600 mb-5">
                            <div class="flex items-center gap-2">
                                <i data-lucide="building-2" class="w-4 h-4 text-red-400 flex-shrink-0"></i>
                                <span class="truncate">{{ $request->hospital_name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-red-400 flex-shrink-0"></i>
                                <span>{{ $request->district }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-red-400 flex-shrink-0"></i>
                                <span>Needed by: {{ \Carbon\Carbon::parse($request->required_date)->format('d M, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="droplets" class="w-4 h-4 text-red-400 flex-shrink-0"></i>
                                <span>{{ $request->bags_required }} bag(s) required</span>
                            </div>
                        </div>

                        <a href="{{ route('requests.show', $request) }}"
                           class="btn-primary w-full text-center text-sm flex items-center justify-center gap-2">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            View Details
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-8 md:hidden">
                <a href="{{ route('requests.index') }}" class="btn-outline inline-flex items-center gap-2">
                    View All Requests <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        @endif
    </div>
</section>

{{-- ============================================================ --}}
{{-- BLOOD GROUP STATISTICS --}}
{{-- ============================================================ --}}
<section class="section bg-gradient-to-br from-red-50 to-pink-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="section-title">Donor Blood Group Distribution</h2>
            <p class="section-subtitle">See which blood groups are most needed in our network</p>
        </div>

        @php
            $maxCount = $bloodGroupStats ? max(array_values($bloodGroupStats)) : 1;
            if ($maxCount == 0) $maxCount = 1;
        @endphp

        <div class="max-w-3xl mx-auto space-y-4">
            @foreach($bloodGroupStats as $group => $count)
                @php $percent = round(($count / $maxCount) * 100); @endphp
                <div class="flex items-center gap-4">
                    <div class="blood-group-badge w-16 text-center flex-shrink-0">{{ $group }}</div>
                    <div class="flex-1 bg-white rounded-full h-8 shadow-inner overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-red-500 to-red-700 rounded-full flex items-center justify-end pr-3 transition-all duration-700"
                             style="width: {{ max($percent, 8) }}%">
                            <span class="text-white text-xs font-bold">{{ $count }}</span>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500 w-20 text-right flex-shrink-0">{{ $count }} donors</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- WHY DONATE BLOOD --}}
{{-- ============================================================ --}}
<section class="section bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-14">
            <h2 class="section-title">Why Donate Blood?</h2>
            <p class="section-subtitle">Your donation is a gift of life — here's why it matters</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="card text-center group hover:border-red-200 hover:shadow-xl transition-all duration-200">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-2xl mb-5 mx-auto group-hover:bg-red-600 transition-colors duration-200">
                    <i data-lucide="heart" class="w-8 h-8 text-red-600 group-hover:text-white transition-colors duration-200"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Save Lives</h3>
                <p class="text-gray-600">One donation can save up to 3 lives. Blood is needed every 2 seconds in Bangladesh hospitals.</p>
            </div>
            <div class="card text-center group hover:border-red-200 hover:shadow-xl transition-all duration-200">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-2xl mb-5 mx-auto group-hover:bg-green-600 transition-colors duration-200">
                    <i data-lucide="zap" class="w-8 h-8 text-green-600 group-hover:text-white transition-colors duration-200"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Quick Recovery</h3>
                <p class="text-gray-600">Your body replenishes donated blood within 4–8 weeks. You'll feel good knowing you helped someone.</p>
            </div>
            <div class="card text-center group hover:border-red-200 hover:shadow-xl transition-all duration-200">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-2xl mb-5 mx-auto group-hover:bg-blue-600 transition-colors duration-200">
                    <i data-lucide="stethoscope" class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors duration-200"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Free Health Checkup</h3>
                <p class="text-gray-600">Every donor gets a mini health checkup including blood pressure, hemoglobin, and blood type testing — absolutely free.</p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- TESTIMONIALS --}}
{{-- ============================================================ --}}
@if($testimonials->isNotEmpty())
<section class="section bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="section-title">What People Say</h2>
            <p class="section-subtitle">Real stories from our community of donors and recipients</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($testimonials as $testimonial)
                <div class="card relative">
                    <div class="absolute top-5 right-5 opacity-10">
                        <i data-lucide="quote" class="w-12 h-12 text-red-600"></i>
                    </div>
                    <i data-lucide="quote" class="w-6 h-6 text-red-400 mb-3"></i>
                    <p class="text-gray-700 italic leading-relaxed mb-5">"{{ $testimonial->message }}"</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-gray-800 text-sm truncate">{{ $testimonial->name }}</div>
                            <div class="text-xs text-gray-500">{{ $testimonial->location }}</div>
                        </div>
                        @if($testimonial->blood_group)
                            <span class="blood-group-badge text-xs flex-shrink-0">{{ $testimonial->blood_group }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================================ --}}
{{-- FAQ SECTION --}}
{{-- ============================================================ --}}
@if($faqs->isNotEmpty())
<section class="section bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="section-subtitle">Everything you need to know about blood donation</p>
        </div>
        <div class="max-w-3xl mx-auto space-y-3" x-data="{ openFaq: null }">
            @foreach($faqs as $index => $faq)
                <div class="card overflow-hidden transition-all duration-200"
                     :class="{ 'ring-2 ring-red-200': openFaq === {{ $index }} }">
                    <button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                            class="w-full flex items-center justify-between p-5 text-left gap-4 group">
                        <span class="font-semibold text-gray-800 group-hover:text-red-700 transition-colors">
                            {{ $faq->question }}
                        </span>
                        <i data-lucide="chevron-down"
                           class="w-5 h-5 text-red-500 flex-shrink-0 transition-transform duration-300"
                           :class="{ 'rotate-180': openFaq === {{ $index }} }"></i>
                    </button>
                    <div x-show="openFaq === {{ $index }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="px-5 pb-5 text-gray-600 leading-relaxed border-t border-gray-100 pt-4">
                        {{ $faq->answer }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================================ --}}
{{-- CTA BANNER --}}
{{-- ============================================================ --}}
<section class="hero-gradient relative overflow-hidden py-20">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-5 left-10">
            <svg width="80" height="104" viewBox="0 0 100 130" fill="white"><path d="M50 5 C50 5 15 50 15 75 C15 97 30.5 115 50 115 C69.5 115 85 97 85 75 C85 50 50 5 50 5Z"/></svg>
        </div>
        <div class="absolute bottom-5 right-20">
            <svg width="60" height="78" viewBox="0 0 100 130" fill="white"><path d="M50 5 C50 5 15 50 15 75 C15 97 30.5 115 50 115 C69.5 115 85 97 85 75 C85 50 50 5 50 5Z"/></svg>
        </div>
    </div>
    <div class="container mx-auto px-4 text-center relative z-10">
        <h2 class="text-4xl md:text-5xl font-black text-white mb-4">Ready to Save a Life?</h2>
        <p class="text-red-100 text-lg mb-8 max-w-xl mx-auto">Join thousands of voluntary donors across Bangladesh. Your blood can be someone's second chance at life.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}"
               class="inline-flex items-center justify-center gap-2 bg-white text-red-700 font-bold px-8 py-4 rounded-xl shadow-xl hover:bg-red-50 hover:scale-105 transition-all duration-200 text-lg">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                Register as Donor
            </a>
            <a href="{{ route('donors.index') }}"
               class="inline-flex items-center justify-center gap-2 border-2 border-white text-white font-bold px-8 py-4 rounded-xl hover:bg-white/20 transition-all duration-200 text-lg">
                <i data-lucide="search" class="w-5 h-5"></i>
                Find Donors Now
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
