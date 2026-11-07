@extends('layouts.dashboard')

@section('title', 'New Blood Request')

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="{{ route('dashboard.index') }}" class="hover:text-red-600 transition-colors">Dashboard</a>
    <i data-lucide="chevron-right" class="w-4 h-4"></i>
    <a href="{{ route('dashboard.requests.index') }}" class="hover:text-red-600 transition-colors">My Requests</a>
    <i data-lucide="chevron-right" class="w-4 h-4"></i>
    <span class="text-gray-900 font-medium">New Request</span>
</nav>

{{-- Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
        <i data-lucide="plus-circle" class="w-6 h-6 text-red-500"></i>
        New Blood Request
    </h1>
    <p class="text-sm text-gray-500 mt-1">Fill in the details below to submit a blood request.</p>
</div>

<form method="POST" action="{{ route('dashboard.requests.store') }}" class="space-y-6">
    @csrf

    {{-- Section 1: Patient Information --}}
    <div class="card">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                <span class="text-red-600 text-sm font-bold">1</span>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-800">Patient Information</h2>
                <p class="text-xs text-gray-400">Details about the patient who needs blood.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

            {{-- Patient Name --}}
            <div class="lg:col-span-2">
                <label for="patient_name" class="label">Patient Name <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="patient_name"
                    name="patient_name"
                    value="{{ old('patient_name') }}"
                    placeholder="Full name of the patient"
                    class="input w-full @error('patient_name') border-red-400 @enderror"
                    required
                >
                @error('patient_name')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Blood Group --}}
            <div>
                <label for="blood_group" class="label">Blood Group <span class="text-red-500">*</span></label>
                <select
                    id="blood_group"
                    name="blood_group"
                    class="select w-full @error('blood_group') border-red-400 @enderror"
                    required
                >
                    <option value="">Select Blood Group</option>
                    @foreach($bloodGroups ?? ['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}" {{ old('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
                @error('blood_group')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Bags Required --}}
            <div>
                <label for="bags_required" class="label">Bags Required <span class="text-red-500">*</span></label>
                <input
                    type="number"
                    id="bags_required"
                    name="bags_required"
                    value="{{ old('bags_required', 1) }}"
                    min="1"
                    max="10"
                    class="input w-full @error('bags_required') border-red-400 @enderror"
                    required
                >
                @error('bags_required')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>

    {{-- Section 2: Hospital Information --}}
    <div class="card">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                <span class="text-blue-600 text-sm font-bold">2</span>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-800">Hospital Information</h2>
                <p class="text-xs text-gray-400">Where the blood is needed.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Hospital Name --}}
            <div>
                <label for="hospital_name" class="label">Hospital Name <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="hospital_name"
                    name="hospital_name"
                    value="{{ old('hospital_name') }}"
                    placeholder="e.g. Dhaka Medical College Hospital"
                    class="input w-full @error('hospital_name') border-red-400 @enderror"
                    required
                >
                @error('hospital_name')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- District --}}
            <div>
                <label for="district" class="label">District <span class="text-red-500">*</span></label>
                <select
                    id="district"
                    name="district"
                    class="select w-full @error('district') border-red-400 @enderror"
                    required
                >
                    <option value="">Select District</option>
                    @foreach($districts ?? [] as $district)
                        <option value="{{ $district }}"
                            {{ old('district', auth()->user()->district) === $district ? 'selected' : '' }}>
                            {{ $district }}
                        </option>
                    @endforeach
                </select>
                @error('district')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Hospital Address --}}
            <div class="md:col-span-2">
                <label for="hospital_address" class="label">Hospital Address</label>
                <textarea
                    id="hospital_address"
                    name="hospital_address"
                    rows="2"
                    placeholder="Full address of the hospital..."
                    class="input w-full resize-none @error('hospital_address') border-red-400 @enderror"
                >{{ old('hospital_address') }}</textarea>
                @error('hospital_address')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Area --}}
            <div>
                <label for="area" class="label">Area / Thana</label>
                <input
                    type="text"
                    id="area"
                    name="area"
                    value="{{ old('area') }}"
                    placeholder="e.g. Mirpur, Dhanmondi"
                    class="input w-full @error('area') border-red-400 @enderror"
                >
                @error('area')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>

    {{-- Section 3: Request Details --}}
    <div class="card" x-data="{ emergency: '{{ old('emergency_level', 'normal') }}' }">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
            <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0">
                <span class="text-orange-600 text-sm font-bold">3</span>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-800">Request Details</h2>
                <p class="text-xs text-gray-400">Timing and urgency information.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Required Date --}}
            <div>
                <label for="required_date" class="label">Required Date <span class="text-red-500">*</span></label>
                <input
                    type="date"
                    id="required_date"
                    name="required_date"
                    value="{{ old('required_date') }}"
                    min="{{ date('Y-m-d') }}"
                    class="input w-full @error('required_date') border-red-400 @enderror"
                    required
                >
                @error('required_date')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Contact Number --}}
            <div>
                <label for="contact_number" class="label">Contact Number <span class="text-red-500">*</span></label>
                <input
                    type="tel"
                    id="contact_number"
                    name="contact_number"
                    value="{{ old('contact_number', auth()->user()->phone ?? '') }}"
                    placeholder="+880 1XXX-XXXXXX"
                    class="input w-full @error('contact_number') border-red-400 @enderror"
                    required
                >
                @error('contact_number')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Emergency Level --}}
            <div class="md:col-span-2">
                <label class="label">Emergency Level <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-1">

                    {{-- Normal --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="emergency_level" value="normal" x-model="emergency"
                               class="sr-only peer">
                        <div class="border-2 rounded-xl p-4 transition-all peer-checked:border-gray-500 peer-checked:bg-gray-50 border-gray-200 hover:border-gray-300">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                                <span class="text-sm font-semibold text-gray-700">Normal</span>
                            </div>
                            <p class="text-xs text-gray-400">Not immediately urgent. Can wait a few days.</p>
                        </div>
                        <div class="absolute top-3 right-3 w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-gray-600 peer-checked:bg-gray-600 flex items-center justify-center">
                        </div>
                    </label>

                    {{-- Urgent --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="emergency_level" value="urgent" x-model="emergency"
                               class="sr-only peer">
                        <div class="border-2 rounded-xl p-4 transition-all peer-checked:border-orange-400 peer-checked:bg-orange-50 border-gray-200 hover:border-orange-200">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-3 h-3 rounded-full bg-orange-400"></div>
                                <span class="text-sm font-semibold text-orange-700">Urgent</span>
                            </div>
                            <p class="text-xs text-gray-400">Needed within 24–48 hours.</p>
                        </div>
                    </label>

                    {{-- Critical --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="emergency_level" value="critical" x-model="emergency"
                               class="sr-only peer">
                        <div class="border-2 rounded-xl p-4 transition-all peer-checked:border-red-500 peer-checked:bg-red-50 border-gray-200 hover:border-red-200">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-3 h-3 rounded-full bg-red-500 animate-pulse"></div>
                                <span class="text-sm font-semibold text-red-700">Critical</span>
                            </div>
                            <p class="text-xs text-gray-400">Life-threatening. Needed immediately.</p>
                        </div>
                    </label>

                </div>
                @error('emergency_level')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror

                {{-- Critical warning --}}
                <div x-show="emergency === 'critical'" x-transition class="mt-3 alert-error flex items-center gap-2 text-sm">
                    <i data-lucide="alert-triangle" class="w-4 h-4 flex-shrink-0"></i>
                    Critical requests will be prioritized and an alert will be sent to nearby donors immediately.
                </div>
            </div>

            {{-- Additional Info --}}
            <div class="md:col-span-2">
                <label for="additional_info" class="label">Additional Information</label>
                <textarea
                    id="additional_info"
                    name="additional_info"
                    rows="3"
                    placeholder="Any other relevant details (ward number, special blood requirements, etc.)..."
                    class="input w-full resize-none @error('additional_info') border-red-400 @enderror"
                >{{ old('additional_info') }}</textarea>
                @error('additional_info')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>

    {{-- Submit --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
        <a href="{{ route('dashboard.requests.index') }}" class="btn-outline flex items-center gap-2 w-full sm:w-auto justify-center">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Cancel
        </a>
        <button type="submit" class="btn-primary flex items-center gap-2 w-full sm:w-auto justify-center px-8">
            <i data-lucide="send" class="w-4 h-4"></i>
            Submit Blood Request
        </button>
    </div>

</form>

@endsection
