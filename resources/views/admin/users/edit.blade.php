@extends('layouts.admin')

@section('title', 'Edit User: ' . $user->name)

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">Dashboard</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
    <a href="{{ route('admin.users.index') }}" class="hover:text-red-600">Users</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
    <span class="text-red-600 font-medium">Edit #{{ $user->id }}</span>
@endsection

@section('content')

<div class="max-w-3xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit User Account</h1>
        <p class="text-sm text-gray-500 mt-1">Modify user role, active status, and personal details.</p>
    </div>

    <div class="card p-6 md:p-8">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
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
                    <label for="role" class="label">Role <span class="text-red-500">*</span></label>
                    <select id="role" name="role" class="select @error('role') input-error @enderror" required>
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Regular User</option>
                        <option value="donor" {{ old('role', $user->role) === 'donor' ? 'selected' : '' }}>Donor</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
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

                <div>
                    <label for="area" class="label">Area / Thana</label>
                    <input type="text" id="area" name="area" value="{{ old('area', $user->area) }}"
                           placeholder="e.g. Mirpur" class="input @error('area') input-error @enderror">
                    @error('area')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                           class="rounded text-red-600 focus:ring-red-500">
                    <span class="text-sm font-semibold text-gray-800">Account is Active</span>
                </label>
                <p class="text-xs text-gray-400 mt-0.5 ml-7">Deactivated users cannot log into the platform.</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.users.show', $user) }}" class="btn-outline text-sm">Cancel</a>
                <button type="submit" class="btn-primary text-sm">
                    <i data-lucide="check" class="w-4 h-4"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
