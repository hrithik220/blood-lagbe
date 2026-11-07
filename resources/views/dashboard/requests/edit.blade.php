@extends('layouts.dashboard')

@section('title', 'Edit Blood Request')

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="{{ route('dashboard.index') }}" class="hover:text-red-600 transition-colors">Dashboard</a>
    <i data-lucide="chevron-right" class="w-4 h-4"></i>
    <a href="{{ route('dashboard.requests.index') }}" class="hover:text-red-600 transition-colors">My Requests</a>
    <i data-lucide="chevron-right" class="w-4 h-4"></i>
    <span class="text-gray-900 font-medium">Edit Request #{{ $bloodRequest->id }}</span>
</nav>

{{-- Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
        <i data-lucide="edit" class="w-6 h-6 text-red-500"></i>
        Edit Blood Request
    </h1>
    <p class="text-sm text-gray-500 mt-1">Update your blood request details. Modifying details will resubmit the request for review.</p>
</div>

<form method="POST" action="{{ route('dashboard.requests.update', $bloodRequest) }}" class="space-y-6">
    @csrf
    @method('PATCH')

    {{-- Section 1: Patient Information --}}
    <div class="card p-6">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                <span class="text-red-600 text-sm font-bold">1</span>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-800">Patient Information</h2>
                <p class="text-xs text-gray-400">Details about the patient who needs blood.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="md:col-span-2">
                <label for="patient_name" class="label">Patient Name <span class="text-red-500">*</span></label>
                <input type="text" id="patient_name" name="patient_name"
                       value="{{ old('patient_name', $bloodRequest->patient_name) }}"
                       class="input @error('patient_name') input-error @enderror" required>
                @error('patient_name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="blood_group" class="label">Blood Group <span class="text-red-500">*</span></label>
                <select id="blood_group" name="blood_group" class="select @error('blood_group') input-error @enderror" required>
                    @foreach($bloodGroups as $bg)
                        <option value="{{ $bg }}" {{ old('blood_group', $bloodRequest->blood_group) === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
                @error('blood_group')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Section 2: Hospital & Location --}}
    <div class="card p-6">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                <span class="text-red-600 text-sm font-bold">2</span>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-800">Hospital & Location</h2>
                <p class="text-xs text-gray-400">Where the patient is admitted and where transfusion will happen.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="hospital_name" class="label">Hospital / Clinic Name <span class="text-red-500">*</span></label>
                <input type="text" id="hospital_name" name="hospital_name"
                       value="{{ old('hospital_name', $bloodRequest->hospital_name) }}"
                       class="input @error('hospital_name') input-error @enderror" required>
                @error('hospital_name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="district" class="label">District <span class="text-red-500">*</span></label>
                <select id="district" name="district" class="select @error('district') input-error @enderror" required>
                    @foreach($districts as $d)
                        <option value="{{ $d }}" {{ old('district', $bloodRequest->district) === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
                @error('district')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="area" class="label">Specific Area / Thana</label>
                <input type="text" id="area" name="area"
                       value="{{ old('area', $bloodRequest->area) }}"
                       class="input @error('area') input-error @enderror">
                @error('area')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="hospital_address" class="label">Hospital Address / Ward / Room <span class="text-red-500">*</span></label>
                <input type="text" id="hospital_address" name="hospital_address"
                       value="{{ old('hospital_address', $bloodRequest->hospital_address) }}"
                       class="input @error('hospital_address') input-error @enderror" required>
                @error('hospital_address')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Section 3: Request Specifications --}}
    <div class="card p-6">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                <span class="text-red-600 text-sm font-bold">3</span>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-800">Requirement Specifications</h2>
                <p class="text-xs text-gray-400">Date, urgency, and contact instructions.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label for="bags_needed" class="label">Bags Needed <span class="text-red-500">*</span></label>
                <input type="number" id="bags_needed" name="bags_needed" min="1" max="10"
                       value="{{ old('bags_needed', $bloodRequest->bags_needed) }}"
                       class="input @error('bags_needed') input-error @enderror" required>
                @error('bags_needed')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="required_date" class="label">Required By Date <span class="text-red-500">*</span></label>
                <input type="date" id="required_date" name="required_date"
                       value="{{ old('required_date', $bloodRequest->required_date?->format('Y-m-d')) }}"
                       class="input @error('required_date') input-error @enderror" required>
                @error('required_date')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="emergency_level" class="label">Emergency Level <span class="text-red-500">*</span></label>
                <select id="emergency_level" name="emergency_level" class="select @error('emergency_level') input-error @enderror" required>
                    <option value="normal" {{ old('emergency_level', $bloodRequest->emergency_level) === 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="urgent" {{ old('emergency_level', $bloodRequest->emergency_level) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="critical" {{ old('emergency_level', $bloodRequest->emergency_level) === 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
                @error('emergency_level')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-3">
                <label for="contact_number" class="label">Emergency Contact Phone Number <span class="text-red-500">*</span></label>
                <input type="text" id="contact_number" name="contact_number"
                       value="{{ old('contact_number', $bloodRequest->contact_number) }}"
                       class="input @error('contact_number') input-error @enderror" required>
                @error('contact_number')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-3">
                <label for="notes" class="label">Additional Notes or Special Instructions</label>
                <textarea id="notes" name="notes" rows="3"
                          class="input @error('notes') input-error @enderror">{{ old('notes', $bloodRequest->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-4">
        <a href="{{ route('dashboard.requests.show', $bloodRequest) }}" class="btn-outline">Cancel</a>
        <button type="submit" class="btn-primary">
            <i data-lucide="check" class="w-4 h-4"></i> Save & Resubmit
        </button>
    </div>
</form>

@endsection
