@extends('layouts.dashboard')

@section('title', 'Donor Profile')

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="{{ route('dashboard.index') }}" class="hover:text-red-600 transition-colors">Dashboard</a>
    <i data-lucide="chevron-right" class="w-4 h-4"></i>
    <span class="text-gray-900 font-medium">Donor Profile</span>
</nav>

{{-- Header --}}
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <i data-lucide="heart" class="w-6 h-6 text-red-500"></i>
            Donor Profile & Availability
        </h1>
        <p class="text-sm text-gray-500 mt-1">Manage your voluntary blood donor registration, status, and availability.</p>
    </div>

    @if($donorProfile)
        <div>
            <form method="POST" action="{{ route('dashboard.donor-profile.toggle-availability') }}">
                @csrf
                <button type="submit" class="{{ $donorProfile->is_available ? 'btn-outline border-amber-300 text-amber-700 hover:bg-amber-50' : 'btn-success' }} text-sm py-2 px-4 shadow-sm">
                    <i data-lucide="{{ $donorProfile->is_available ? 'pause-circle' : 'play-circle' }}" class="w-4 h-4"></i>
                    {{ $donorProfile->is_available ? 'Pause Availability' : 'Mark as Available' }}
                </button>
            </form>
        </div>
    @endif
</div>

<div class="space-y-8 max-w-4xl">

    @if($donorProfile)
        {{-- Profile Status Banner --}}
        <div class="card p-6 border-l-4 {{ $donorProfile->is_available ? 'border-green-500 bg-green-50/20' : 'border-gray-400 bg-gray-50' }}">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="blood-group-badge-lg">
                        {{ $donorProfile->blood_group }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-bold text-gray-900">{{ $user->name }}</h2>
                            @if($donorProfile->is_available)
                                <span class="badge badge-green flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-green-500 animate-ping"></span> Available
                                </span>
                            @else
                                <span class="badge badge-gray">Currently Paused</span>
                            @endif
                            <span class="badge {{ $donorProfile->is_public ? 'badge-blue' : 'badge-orange' }}">
                                {{ $donorProfile->is_public ? 'Public in Search' : 'Hidden from Search' }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $donorProfile->district }}{{ $donorProfile->area ? ', ' . $donorProfile->area : '' }} • Phone: {{ $donorProfile->phone }}
                        </p>
                    </div>
                </div>

                <div class="text-right sm:border-l sm:border-gray-200 sm:pl-6">
                    <p class="text-xs uppercase text-gray-400 font-semibold tracking-wider">Last Donated</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5">
                        {{ $donorProfile->last_donation_date ? $donorProfile->last_donation_date->format('d M, Y') : 'No record' }}
                    </p>
                    @if($donorProfile->last_donation_date)
                        <p class="text-xs text-gray-500 mt-0.5">{{ $donorProfile->last_donation_date->diffForHumans() }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Donation Eligibility Health Notice --}}
        @php
            $daysSinceDonation = $donorProfile->last_donation_date ? (int) $donorProfile->last_donation_date->diffInDays(now()) : 999;
        @endphp
        @if($daysSinceDonation < 90)
            <div class="alert-info p-5 rounded-2xl flex items-start gap-3">
                <i data-lucide="info" class="w-5 h-5 flex-shrink-0 text-blue-600 mt-0.5"></i>
                <div class="text-xs text-blue-900 leading-relaxed">
                    <strong class="font-bold">Medical Reminder:</strong> Your last donation was approximately {{ $daysSinceDonation }} days ago. Medical organizations generally recommend a 90-day recovery interval between whole blood donations to replenish iron and hemoglobin. If contacted, please consult your physician.
                </div>
            </div>
        @else
            <div class="alert-success p-5 rounded-2xl flex items-start gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 text-green-600 mt-0.5"></i>
                <div class="text-xs text-green-900 leading-relaxed">
                    <strong class="font-bold">Ready to Donate:</strong> It has been more than 90 days since your last recorded donation (or no prior date recorded). Keep your availability active to receive notifications about matching emergency requests.
                </div>
            </div>
        @endif
    @else
        {{-- If User Has Not Created Donor Profile Yet --}}
        <div class="card p-8 text-center border-2 border-dashed border-blood-200 bg-blood-50/20">
            <div class="w-16 h-16 bg-blood-100 text-blood-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="heart-handshake" class="w-8 h-8"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Register as a Blood Donor</h2>
            <p class="text-gray-600 text-sm max-w-md mx-auto mb-6">
                You haven't set up your blood donor profile yet. Fill in your blood group and location below to join our network of lifesavers.
            </p>
        </div>
    @endif

    {{-- Edit / Create Profile Form --}}
    <div class="card p-6 md:p-8">
        <div class="pb-4 mb-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">{{ $donorProfile ? 'Update Donor Information' : 'Complete Donor Registration' }}</h2>
            <p class="text-xs text-gray-500">Accurate location and blood group ensure you are notified for the right emergencies.</p>
        </div>

        <form method="POST" action="{{ route('dashboard.donor-profile.update') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="blood_group" class="label">Blood Group <span class="text-red-500">*</span></label>
                    <select id="blood_group" name="blood_group" class="select @error('blood_group') input-error @enderror" required>
                        <option value="">Select Blood Group</option>
                        @foreach($bloodGroups as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group', $donorProfile->blood_group ?? '') === $bg ? 'selected' : '' }}>
                                {{ $bg }}
                            </option>
                        @endforeach
                    </select>
                    @error('blood_group')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="label">Contact Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" id="phone" name="phone"
                           value="{{ old('phone', $donorProfile->phone ?? $user->phone) }}"
                           placeholder="017XXXXXXXX" class="input @error('phone') input-error @enderror" required>
                    @error('phone')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="district" class="label">District <span class="text-red-500">*</span></label>
                    <select id="district" name="district" class="select @error('district') input-error @enderror" required>
                        <option value="">Select District</option>
                        @foreach($districts as $d)
                            <option value="{{ $d }}" {{ old('district', $donorProfile->district ?? $user->district) === $d ? 'selected' : '' }}>
                                {{ $d }}
                            </option>
                        @endforeach
                    </select>
                    @error('district')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="area" class="label">Area / Thana</label>
                    <input type="text" id="area" name="area"
                           value="{{ old('area', $donorProfile->area ?? $user->area) }}"
                           placeholder="e.g. Mirpur, Gulshan" class="input @error('area') input-error @enderror">
                    @error('area')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="last_donation_date" class="label">Last Donation Date (if applicable)</label>
                    <input type="date" id="last_donation_date" name="last_donation_date"
                           value="{{ old('last_donation_date', $donorProfile?->last_donation_date?->format('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}" class="input @error('last_donation_date') input-error @enderror">
                    <p class="text-xs text-gray-400 mt-1">Leave blank if you have never donated before.</p>
                    @error('last_donation_date')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="bio" class="label">Short Bio or Motivation (optional)</label>
                    <textarea id="bio" name="bio" rows="3"
                              placeholder="e.g. Ready to donate blood during critical surgery hours in Mirpur area."
                              class="input @error('bio') input-error @enderror">{{ old('bio', $donorProfile->bio ?? '') }}</textarea>
                    @error('bio')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Checkboxes --}}
            <div class="space-y-3 pt-3 border-t border-gray-100">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="is_available" value="1"
                           {{ old('is_available', $donorProfile->is_available ?? true) ? 'checked' : '' }}
                           class="rounded text-blood-600 focus:ring-blood-500 mt-1">
                    <div>
                        <span class="text-sm font-semibold text-gray-800">I am currently available to donate blood</span>
                        <p class="text-xs text-gray-500">Uncheck this anytime if you are traveling, unwell, or recently donated.</p>
                    </div>
                </label>

                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="is_public" value="1"
                           {{ old('is_public', $donorProfile->is_public ?? true) ? 'checked' : '' }}
                           class="rounded text-blood-600 focus:ring-blood-500 mt-1">
                    <div>
                        <span class="text-sm font-semibold text-gray-800">List my donor profile in public searches</span>
                        <p class="text-xs text-gray-500">Your phone number remains protected from anonymous visitors.</p>
                    </div>
                </label>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="btn-primary">
                    <i data-lucide="check" class="w-4 h-4"></i> Save Donor Profile
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
