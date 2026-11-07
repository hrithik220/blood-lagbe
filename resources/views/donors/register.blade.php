@extends('layouts.app')

@section('title', 'Register as Donor - Blood Lagbe')

@section('content')

{{-- ============================================================ --}}
{{-- PAGE HERO --}}
{{-- ============================================================ --}}
<section class="hero-gradient relative overflow-hidden py-16">
    <div class="absolute top-0 right-0 opacity-10" aria-hidden="true">
        <svg width="300" height="390" viewBox="0 0 100 130" fill="white"><path d="M50 5 C50 5 15 50 15 75 C15 97 30.5 115 50 115 C69.5 115 85 97 85 75 C85 50 50 5 50 5Z"/></svg>
    </div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="flex items-center gap-2 text-red-200 text-sm mb-3">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <a href="{{ route('donors.index') }}" class="hover:text-white transition-colors">Donors</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="text-white">Register</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-black text-white mb-3">
            Become a <span class="text-red-200">Donor</span>
        </h1>
        <p class="text-red-100 text-lg max-w-xl">
            Join our network of voluntary blood donors and help save lives across Bangladesh. Registration is free and takes only a few minutes.
        </p>
    </div>
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 40L1440 40L1440 0C1200 30 960 40 720 40C480 40 240 30 0 0Z" fill="#f9fafb"/>
        </svg>
    </div>
</section>

{{-- ============================================================ --}}
{{-- REGISTRATION FORM --}}
{{-- ============================================================ --}}
<section class="section bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-8 max-w-6xl mx-auto">

            {{-- ===================== LEFT: FORM ===================== --}}
            <div class="lg:col-span-2">
                <div class="card shadow-lg">
                    <div class="flex items-center gap-3 mb-6 pb-5 border-b border-gray-100">
                        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="clipboard-list" class="w-5 h-5 text-red-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Donor Registration</h2>
                            <p class="text-sm text-gray-500">Fill in your details to join our donor network</p>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="alert-error mb-6">
                            <div class="flex items-start gap-2">
                                <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold mb-1">Please fix the following errors:</p>
                                    <ul class="text-sm space-y-1 list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ route('donors.store') }}"
                          x-data="{ agreedToTerms: false, isAvailable: true }">
                        @csrf

                        {{-- ---- Personal Information ---- --}}
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Personal Information</h3>
                        <div class="grid sm:grid-cols-2 gap-5 mb-6">
                            {{-- Name --}}
                            <div>
                                <label for="name" class="label">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" class="input @error('name') border-red-500 @enderror"
                                       placeholder="Your full name"
                                       value="{{ old('name', auth()->user()->name ?? '') }}"
                                       required>
                                @error('name')
                                    <p class="input-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="label">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" class="input @error('email') border-red-500 @enderror"
                                       placeholder="you@example.com"
                                       value="{{ old('email', auth()->user()->email ?? '') }}"
                                       required>
                                @error('email')
                                    <p class="input-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="label">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" id="phone" class="input @error('phone') border-red-500 @enderror"
                                       placeholder="+880 1X XX XXX XXX"
                                       value="{{ old('phone') }}"
                                       required>
                                @error('phone')
                                    <p class="input-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Blood Group --}}
                            <div>
                                <label for="blood_group" class="label">
                                    Blood Group <span class="text-red-500">*</span>
                                </label>
                                <select name="blood_group" id="blood_group" class="select @error('blood_group') border-red-500 @enderror" required>
                                    <option value="">Select Blood Group</option>
                                    @foreach($bloodGroups as $bg)
                                        <option value="{{ $bg }}" {{ old('blood_group') === $bg ? 'selected' : '' }}>
                                            {{ $bg }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('blood_group')
                                    <p class="input-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- ---- Location ---- --}}
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Location</h3>
                        <div class="grid sm:grid-cols-2 gap-5 mb-6">
                            {{-- District --}}
                            <div>
                                <label for="district" class="label">
                                    District <span class="text-red-500">*</span>
                                </label>
                                <select name="district" id="district" class="select @error('district') border-red-500 @enderror" required>
                                    <option value="">Select District</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district }}" {{ old('district') === $district ? 'selected' : '' }}>
                                            {{ $district }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('district')
                                    <p class="input-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Area --}}
                            <div>
                                <label for="area" class="label">Area / Upazila</label>
                                <input type="text" name="area" id="area" class="input @error('area') border-red-500 @enderror"
                                       placeholder="e.g. Mirpur, Dhanmondi"
                                       value="{{ old('area') }}">
                                @error('area')
                                    <p class="input-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- ---- Donation Details ---- --}}
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Donation Details</h3>
                        <div class="grid sm:grid-cols-2 gap-5 mb-6">
                            {{-- Last Donation Date --}}
                            <div>
                                <label for="last_donation_date" class="label">Last Donation Date</label>
                                <input type="date" name="last_donation_date" id="last_donation_date"
                                       class="input @error('last_donation_date') border-red-500 @enderror"
                                       value="{{ old('last_donation_date') }}"
                                       max="{{ date('Y-m-d') }}">
                                <p class="text-xs text-gray-400 mt-1">Leave blank if you've never donated before</p>
                                @error('last_donation_date')
                                    <p class="input-error">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Availability --}}
                            <div class="flex flex-col justify-center">
                                <label class="label mb-3">Availability Status</label>
                                <label class="flex items-center gap-3 cursor-pointer group" @click="isAvailable = !isAvailable">
                                    <div class="relative">
                                        <input type="checkbox" name="is_available" id="is_available" value="1"
                                               class="sr-only"
                                               {{ old('is_available', '1') == '1' ? 'checked' : '' }}
                                               x-model="isAvailable">
                                        <div class="w-11 h-6 rounded-full transition-colors duration-200"
                                             :class="isAvailable ? 'bg-green-500' : 'bg-gray-300'"></div>
                                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200"
                                             :class="isAvailable ? 'translate-x-5' : 'translate-x-0'"></div>
                                    </div>
                                    <span class="font-medium text-sm" :class="isAvailable ? 'text-green-700' : 'text-gray-500'">
                                        <span x-show="isAvailable">Available to Donate</span>
                                        <span x-show="!isAvailable">Not Available Right Now</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- Bio --}}
                        <div class="mb-6">
                            <label for="bio" class="label">About You <span class="text-gray-400 font-normal">(optional)</span></label>
                            <textarea name="bio" id="bio" rows="3"
                                      class="input @error('bio') border-red-500 @enderror resize-none"
                                      placeholder="Tell potential recipients a little about yourself and your donation experience... (max 500 characters)"
                                      maxlength="500">{{ old('bio') }}</textarea>
                            @error('bio')
                                <p class="input-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ---- Consent ---- --}}
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="consent" id="consent" value="1"
                                       x-model="agreedToTerms"
                                       class="mt-1 w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                       {{ old('consent') ? 'checked' : '' }}
                                       required>
                                <span class="text-sm text-gray-700 leading-relaxed">
                                    I agree to be listed as a blood donor on <strong>Blood Lagbe</strong>. My contact information will only be shared with verified users who have a genuine blood need. I understand that my profile will be publicly visible but my phone number is protected.
                                </span>
                            </label>
                            @error('consent')
                                <p class="input-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                                class="btn-primary w-full flex items-center justify-center gap-2 text-base py-3"
                                :disabled="!agreedToTerms"
                                :class="{ 'opacity-50 cursor-not-allowed': !agreedToTerms }">
                            <i data-lucide="user-plus" class="w-5 h-5"></i>
                            Register as Donor
                        </button>

                        <p class="text-center text-sm text-gray-500 mt-4">
                            Already a donor?
                            <a href="{{ route('donors.index') }}" class="text-red-600 hover:underline font-medium">Browse donor list</a>
                        </p>
                    </form>
                </div>
            </div>

            {{-- ===================== RIGHT: INFO CARD ===================== --}}
            <div class="space-y-6">
                {{-- Why Donate --}}
                <div class="card border-red-100">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="heart" class="w-5 h-5 text-red-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Why Become a Donor?</h3>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="shield-check" class="w-4 h-4 text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">Save Up to 3 Lives</p>
                                <p class="text-xs text-gray-500 mt-0.5">One donation can be separated into multiple components</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="stethoscope" class="w-4 h-4 text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">Free Health Screening</p>
                                <p class="text-xs text-gray-500 mt-0.5">Blood pressure, hemoglobin & blood type check at no cost</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="users" class="w-4 h-4 text-purple-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">Join a Community</p>
                                <p class="text-xs text-gray-500 mt-0.5">Be part of thousands of life-saving donors across Bangladesh</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="zap" class="w-4 h-4 text-orange-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">Quick Recovery</p>
                                <p class="text-xs text-gray-500 mt-0.5">Your body replenishes the blood within 4–8 weeks naturally</p>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Eligibility Info --}}
                <div class="card border-blue-100">
                    <div class="flex items-center gap-3 mb-4">
                        <i data-lucide="clipboard-check" class="w-5 h-5 text-blue-600"></i>
                        <h3 class="font-bold text-gray-800">Eligibility Criteria</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 flex-shrink-0"></i>
                            Age 18–65 years
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 flex-shrink-0"></i>
                            Weight ≥ 50 kg
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 flex-shrink-0"></i>
                            Hemoglobin ≥ 12.5 g/dL
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 flex-shrink-0"></i>
                            No donation in last 3 months
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 flex-shrink-0"></i>
                            Generally in good health
                        </li>
                    </ul>
                </div>

                {{-- Privacy --}}
                <div class="bg-gradient-to-br from-red-50 to-pink-50 border border-red-100 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <i data-lucide="lock" class="w-4 h-4 text-red-600"></i>
                        <h3 class="font-bold text-gray-800 text-sm">Your Privacy is Safe</h3>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Your phone number and personal contact details are never shown publicly. Only your name, blood group, and location are displayed to help recipients find you.
                    </p>
                </div>
            </div>
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
