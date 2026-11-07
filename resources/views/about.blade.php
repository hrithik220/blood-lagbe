@extends('layouts.app')

@section('title', 'About Us — Blood Lagbe')
@section('description', 'Learn about Blood Lagbe - our mission, vision, and how we are connecting blood donors and recipients across Bangladesh to save lives.')

@section('content')
{{-- Page Header --}}
<div class="hero-gradient hero-pattern text-white py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm text-white mb-4">
            <i data-lucide="info" class="w-3.5 h-3.5"></i> Our Story & Purpose
        </span>
        <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-4">Saving Lives Together</h1>
        <p class="text-lg md:text-xl text-red-100 max-w-2xl mx-auto">
            "Every Drop Counts. Every Donor Matters." Connecting volunteer blood donors with patients across Bangladesh.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-20">

    {{-- Mission & Vision Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="card p-8 md:p-10 border-l-4 border-blood-600 hover:shadow-lg transition-shadow">
            <div class="w-14 h-14 bg-blood-100 rounded-2xl flex items-center justify-center text-blood-600 mb-6">
                <i data-lucide="target" class="w-7 h-7"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h2>
            <p class="text-gray-600 leading-relaxed text-base">
                To build an accessible, fast, and transparent platform that bridges the critical gap between blood donors and recipients across all 64 districts of Bangladesh. We empower communities to take immediate action during medical emergencies and ensure that no life is lost due to unavailability of safe blood.
            </p>
        </div>

        <div class="card p-8 md:p-10 border-l-4 border-red-500 hover:shadow-lg transition-shadow">
            <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center text-red-600 mb-6">
                <i data-lucide="eye" class="w-7 h-7"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h2>
            <p class="text-gray-600 leading-relaxed text-base">
                To create a compassionate Bangladesh where finding blood during emergencies is immediate, hassle-free, and dependable. We envision a sustainable voluntary blood donation culture driven by modern technology, mutual empathy, and strict adherence to patient privacy and medical standards.
            </p>
        </div>
    </div>

    {{-- How It Works --}}
    <div>
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="section-title">How Blood Lagbe Works</h2>
            <p class="section-subtitle">A seamless process designed for high urgency and maximum reliability.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card p-8 text-center relative hover:shadow-md transition-shadow">
                <div class="w-16 h-16 bg-blood-50 text-blood-600 font-extrabold text-2xl rounded-2xl flex items-center justify-center mx-auto mb-6 border border-blood-200">
                    1
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Register or Search</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Volunteers register their blood group and district. Anyone in immediate need can search for active, eligible donors or post a formal request.
                </p>
            </div>

            <div class="card p-8 text-center relative hover:shadow-md transition-shadow">
                <div class="w-16 h-16 bg-blood-50 text-blood-600 font-extrabold text-2xl rounded-2xl flex items-center justify-center mx-auto mb-6 border border-blood-200">
                    2
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Verification & Match</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Our platform verifies requests and notifies matching donors in the specific hospital area and district immediately without compromising private contacts.
                </p>
            </div>

            <div class="card p-8 text-center relative hover:shadow-md transition-shadow">
                <div class="w-16 h-16 bg-blood-50 text-blood-600 font-extrabold text-2xl rounded-2xl flex items-center justify-center mx-auto mb-6 border border-blood-200">
                    3
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Direct Life Saving</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Donors coordinate with the patient's family, arrive at the medical facility, and complete the transfusion after hospital cross-matching.
                </p>
            </div>
        </div>
    </div>

    {{-- Core Values / Principles --}}
    <div class="bg-gray-100 rounded-3xl p-8 md:p-12">
        <div class="max-w-3xl mx-auto text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Our Core Principles</h2>
            <p class="text-gray-600 mt-2">What sets Blood Lagbe apart as a reliable health-tech service.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <i data-lucide="shield-check" class="w-8 h-8 text-blood-600 mb-4"></i>
                <h4 class="font-bold text-gray-900 mb-2">100% Free</h4>
                <p class="text-xs text-gray-600">Voluntary service with zero commercial fees or transaction costs.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <i data-lucide="lock" class="w-8 h-8 text-blood-600 mb-4"></i>
                <h4 class="font-bold text-gray-900 mb-2">Privacy First</h4>
                <p class="text-xs text-gray-600">Donor contact details are protected from public scraping and spam.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <i data-lucide="zap" class="w-8 h-8 text-blood-600 mb-4"></i>
                <h4 class="font-bold text-gray-900 mb-2">Rapid Response</h4>
                <p class="text-xs text-gray-600">Designed for low latency in urgent surgeries and trauma situations.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <i data-lucide="activity" class="w-8 h-8 text-blood-600 mb-4"></i>
                <h4 class="font-bold text-gray-900 mb-2">Safety Awareness</h4>
                <p class="text-xs text-gray-600">Medical eligibility reminders and encouragement of certified hospital screening.</p>
            </div>
        </div>
    </div>

    {{-- Medical Disclaimer Callout --}}
    <div class="alert-warning p-6 rounded-2xl flex items-start gap-4">
        <i data-lucide="alert-triangle" class="w-6 h-6 flex-shrink-0 text-amber-600 mt-0.5"></i>
        <div class="text-sm text-amber-900">
            <h4 class="font-bold text-base mb-1">Medical Disclaimer & Legal Responsibility</h4>
            <p class="leading-relaxed">
                Blood Lagbe operates purely as an information coordinator connecting willing voluntary donors with individuals seeking blood. Blood Lagbe is not a blood bank, medical clinic, or diagnostic laboratory. All cross-matching, pathogen screening (HIV, Hepatitis, Malaria, Syphilis), and final safety determinations must be carried out by qualified medical professionals at accredited healthcare centers before transfusion.
            </p>
        </div>
    </div>

    {{-- CTA --}}
    <div class="hero-gradient rounded-3xl p-8 md:p-12 text-center text-white">
        <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Be A Hero In Someone's Story</h2>
        <p class="text-red-100 max-w-xl mx-auto mb-8">
            Your single donation can save up to three lives. Sign up now or search for blood requests needing urgent attention.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('donors.register') }}" class="btn-secondary">
                <i data-lucide="heart" class="w-4 h-4"></i> Become a Donor
            </a>
            <a href="{{ route('requests.index') }}" class="btn-outline bg-white/10 text-white border-white/30 hover:bg-white/20">
                <i data-lucide="droplet" class="w-4 h-4"></i> View Blood Requests
            </a>
        </div>
    </div>

</div>
@endsection
