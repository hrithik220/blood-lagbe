@extends('layouts.app')

@section('title', 'Blood Request for ' . $bloodRequest->blood_group . ' — Blood Lagbe')
@section('description', 'Urgent need for ' . $bloodRequest->blood_group . ' blood at ' . $bloodRequest->hospital_name . ' in ' . $bloodRequest->district . '.')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blood-600">Home</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{ route('requests.index') }}" class="hover:text-blood-600">Blood Requests</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-gray-900 font-medium">Request #{{ $bloodRequest->id }}</span>
    </nav>

    @php
        $isCritical = $bloodRequest->emergency_level === 'critical';
        $isUrgent = $bloodRequest->emergency_level === 'urgent';
    @endphp

    {{-- Urgent Notification Banner if Critical --}}
    @if($isCritical || $isUrgent)
        <div class="p-4 mb-6 rounded-2xl flex items-center gap-3 {{ $isCritical ? 'bg-red-50 text-red-800 border border-red-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
            <i data-lucide="{{ $isCritical ? 'alert-octagon' : 'alert-triangle' }}" class="w-6 h-6 flex-shrink-0 {{ $isCritical ? 'text-red-600' : 'text-amber-600' }}"></i>
            <div>
                <p class="font-bold text-sm">
                    {{ $isCritical ? 'CRITICAL EMERGENCY' : 'URGENT REQUIREMENT' }}
                </p>
                <p class="text-xs">This patient requires immediate transfusion. If you are eligible and nearby, please reach out as soon as possible.</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- Left: Main Details (8 cols) --}}
        <div class="lg:col-span-8 space-y-6">
            <div class="card p-8">
                <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="blood-group-badge-lg">
                            {{ $bloodRequest->blood_group }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h1 class="text-2xl font-black text-gray-900">
                                    {{ Str::limit($bloodRequest->patient_name, 2, '***') }}
                                </h1>
                                <span class="badge badge-green">{{ $bloodRequest->bags_needed }} Bag(s)</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Posted by {{ $bloodRequest->user->name ?? 'A family member' }} • {{ $bloodRequest->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    <div>
                        @if($isCritical)
                            <span class="badge badge-red text-sm px-3 py-1.5 flex items-center gap-1.5">
                                <i data-lucide="alert-octagon" class="w-4 h-4"></i> Critical
                            </span>
                        @elseif($isUrgent)
                            <span class="badge badge-orange text-sm px-3 py-1.5 flex items-center gap-1.5">
                                <i data-lucide="alert-triangle" class="w-4 h-4"></i> Urgent
                            </span>
                        @else
                            <span class="badge badge-blue text-sm px-3 py-1.5">Normal</span>
                        @endif
                    </div>
                </div>

                {{-- Key Details Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-gray-100">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Hospital / Medical Center</p>
                        <p class="text-base font-bold text-gray-900">{{ $bloodRequest->hospital_name }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">{{ $bloodRequest->hospital_address }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Location / District</p>
                        <p class="text-base font-bold text-gray-900">{{ $bloodRequest->district }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">{{ $bloodRequest->area ?: 'Area not specified' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Required Date</p>
                        <p class="text-base font-bold text-blood-700 flex items-center gap-1.5">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            {{ $bloodRequest->required_date->format('l, d F Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Request Status</p>
                        <span class="badge badge-{{ $bloodRequest->status_badge_color }} text-xs capitalize">
                            {{ $bloodRequest->status }}
                        </span>
                    </div>
                </div>

                {{-- Additional Information / Medical Notes --}}
                @if($bloodRequest->notes)
                    <div class="pt-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Additional Details & Notes:</h3>
                        <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-700 leading-relaxed border border-gray-200">
                            {{ $bloodRequest->notes }}
                        </div>
                    </div>
                @endif
            </div>

            {{-- Contact Information Section --}}
            <div class="card p-8 border-2 border-blood-100">
                <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center gap-2">
                    <i data-lucide="phone-call" class="w-5 h-5 text-blood-600"></i> Contact the Requester
                </h3>
                <p class="text-xs text-gray-500 mb-6">
                    Please only contact if you are a matching donor ({{ $bloodRequest->blood_group }} or universal compatible) and are ready to donate.
                </p>

                @auth
                    <div class="bg-blood-50 p-6 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 border border-blood-200">
                        <div>
                            <p class="text-xs font-semibold text-blood-800 uppercase tracking-wider">Direct Contact Number</p>
                            <p class="text-2xl font-black text-blood-700 font-mono tracking-wider mt-1">
                                {{ $bloodRequest->contact_number }}
                            </p>
                        </div>
                        <a href="tel:{{ $bloodRequest->contact_number }}" class="btn-primary text-base py-3 px-6 whitespace-nowrap shadow-md">
                            <i data-lucide="phone" class="w-4 h-4"></i> Call Immediately
                        </a>
                    </div>
                @else
                    <div class="bg-gray-50 p-6 rounded-2xl text-center border border-gray-200">
                        <i data-lucide="lock" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                        <h4 class="text-base font-bold text-gray-900 mb-1">Contact Number Protected</h4>
                        <p class="text-xs text-gray-500 max-w-md mx-auto mb-4">
                            To protect patient families from spam and fraud, contact details are visible to signed-in users.
                        </p>
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('login') }}" class="btn-primary btn-sm">Sign In to View</a>
                            <a href="{{ route('register') }}" class="btn-secondary btn-sm">Register</a>
                        </div>
                    </div>
                @endauth
            </div>

            {{-- Medical Disclaimer --}}
            <div class="alert-info p-5 rounded-2xl text-xs leading-relaxed">
                <strong class="font-bold">Important Medical Notice:</strong> Blood Lagbe facilitates peer-to-peer connection for blood donations. Compatibility and medical screening (infectious diseases, cross-matching) must always be performed by hospital personnel prior to transfusion. Never exchange money for blood donation.
            </div>
        </div>

        {{-- Right: Sidebar Actions (4 cols) --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="card p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">Can You Donate?</h3>
                <div class="space-y-3 text-xs text-gray-600">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-green-600 flex-shrink-0"></i>
                        <span>Age between 18 - 60 years old</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-green-600 flex-shrink-0"></i>
                        <span>Weight at least 50 kg</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-green-600 flex-shrink-0"></i>
                        <span>Last donation at least 90 days ago</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-green-600 flex-shrink-0"></i>
                        <span>Feeling healthy and energetic today</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <a href="{{ route('donors.register') }}" class="btn-primary w-full justify-center text-sm py-2.5">
                        <i data-lucide="heart" class="w-4 h-4"></i> Register as a Donor
                    </a>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="text-base font-bold text-gray-900 mb-2">Share This Request</h3>
                <p class="text-xs text-gray-500 mb-4">Help reach more potential donors by sharing on social media or messaging apps.</p>
                
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <a href="https://api.whatsapp.com/send?text={{ urlencode('Urgent Blood Needed (' . $bloodRequest->blood_group . ') at ' . $bloodRequest->hospital_name . ', ' . $bloodRequest->district . '. Check details: ' . url()->current()) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="btn-outline justify-center py-2 text-green-700 hover:border-green-500 hover:bg-green-50">
                        <i data-lucide="message-circle" class="w-4 h-4"></i> WhatsApp
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="btn-outline justify-center py-2 text-blue-700 hover:border-blue-500 hover:bg-blue-50">
                        <i data-lucide="share-2" class="w-4 h-4"></i> Facebook
                    </a>
                </div>
            </div>

            <a href="{{ route('requests.index') }}" class="btn-outline w-full justify-center py-2.5 text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to All Requests
            </a>
        </div>

    </div>

</div>
@endsection
