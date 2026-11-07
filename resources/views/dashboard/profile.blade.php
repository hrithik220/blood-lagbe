@extends('layouts.dashboard')

@section('title', 'Profile Settings')

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="{{ route('dashboard.index') }}" class="hover:text-red-600 transition-colors">Dashboard</a>
    <i data-lucide="chevron-right" class="w-4 h-4"></i>
    <span class="text-gray-900 font-medium">Profile Settings</span>
</nav>

{{-- Header --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
        <i data-lucide="user" class="w-6 h-6 text-red-500"></i>
        Account & Profile Settings
    </h1>
    <p class="text-sm text-gray-500 mt-1">Manage your account information, security credentials, and preferences.</p>
</div>

<div class="space-y-8 max-w-4xl">

    {{-- Card 1: Personal Profile Details --}}
    <div class="card p-6 md:p-8">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Personal Information</h2>
                <p class="text-xs text-gray-500">Update your public name, contact phone, and primary location.</p>
            </div>
            <span class="badge badge-red text-xs uppercase">{{ $user->role }}</span>
        </div>

        <form method="POST" action="{{ route('dashboard.profile.update') }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="label">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                           class="input @error('name') input-error @enderror" required>
                    @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="label">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                           class="input @error('email') input-error @enderror" required>
                    @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="label">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                           placeholder="017XXXXXXXX" class="input @error('phone') input-error @enderror">
                    @error('phone')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="district" class="label">District</label>
                    <input type="text" id="district" name="district" value="{{ old('district', $user->district) }}"
                           placeholder="e.g. Dhaka" class="input @error('district') input-error @enderror">
                    @error('district')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="area" class="label">Area / Thana</label>
                    <input type="text" id="area" name="area" value="{{ old('area', $user->area) }}"
                           placeholder="e.g. Mirpur, Dhanmondi" class="input @error('area') input-error @enderror">
                    @error('area')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-primary">
                    <i data-lucide="check" class="w-4 h-4"></i> Update Information
                </button>
            </div>
        </form>
    </div>

    {{-- Card 2: Password Update --}}
    <div class="card p-6 md:p-8">
        <div class="pb-4 mb-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">Change Password</h2>
            <p class="text-xs text-gray-500">Ensure your account uses a long, random password to stay secure.</p>
        </div>

        <form method="POST" action="{{ route('dashboard.profile.password') }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div class="max-w-lg space-y-4">
                <div>
                    <label for="current_password" class="label">Current Password <span class="text-red-500">*</span></label>
                    <input type="password" id="current_password" name="current_password"
                           class="input @error('current_password') input-error @enderror" required>
                    @error('current_password')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="label">New Password <span class="text-red-500">*</span></label>
                    <input type="password" id="password" name="password"
                           class="input @error('password') input-error @enderror" required>
                    @error('password')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="label">Confirm New Password <span class="text-red-500">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="input" required>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-primary">
                    <i data-lucide="lock" class="w-4 h-4"></i> Update Password
                </button>
            </div>
        </form>
    </div>

    {{-- Card 3: Danger Zone / Delete Account --}}
    <div class="card p-6 md:p-8 border border-red-200 bg-red-50/20" x-data="{ showModal: false }">
        <div class="pb-4 mb-6 border-b border-red-100">
            <h2 class="text-lg font-bold text-red-700">Delete Account</h2>
            <p class="text-xs text-red-600/80">Permanently delete your account and all associated donation data.</p>
        </div>

        <p class="text-sm text-gray-600 mb-6">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>

        <button type="button" @click="showModal = true" class="btn-danger">
            <i data-lucide="trash-2" class="w-4 h-4"></i> Delete Account
        </button>

        {{-- Alpine Confirmation Modal --}}
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6">
                    <form method="POST" action="{{ route('dashboard.profile.destroy') }}">
                        @csrf
                        @method('DELETE')

                        <h3 class="text-lg font-bold text-gray-900 mb-2">Are you sure you want to delete your account?</h3>
                        <p class="text-xs text-gray-500 mb-4">Please enter your password to confirm you would like to permanently delete your account.</p>

                        <div class="mb-5">
                            <label for="delete_password" class="label">Your Password</label>
                            <input type="password" id="delete_password" name="password" class="input" placeholder="Password" required>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <button type="button" @click="showModal = false" class="btn-outline">Cancel</button>
                            <button type="submit" class="btn-danger">Confirm Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
