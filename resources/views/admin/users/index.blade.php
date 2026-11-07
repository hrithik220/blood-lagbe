@extends('layouts.admin')

@section('title', 'Users')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition-colors">Dashboard</a>
    <i data-lucide="chevron-right" class="w-3 h-3"></i>
    <span class="text-red-600 font-medium">Users</span>
@endsection

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Manage Users</h2>
        <p class="text-sm text-gray-500 mt-0.5">View and manage all registered users.</p>
    </div>
</div>

{{-- Search & Filters --}}
<div class="card p-4 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by name or email…"
                    class="input pl-10 py-2 text-sm">
            </div>
        </div>
        <select name="role" class="select py-2 text-sm w-full sm:w-40">
            <option value="">All Roles</option>
            <option value="admin"   {{ request('role') === 'admin'  ? 'selected' : '' }}>Admin</option>
            <option value="donor"   {{ request('role') === 'donor'  ? 'selected' : '' }}>Donor</option>
            <option value="user"    {{ request('role') === 'user'   ? 'selected' : '' }}>User</option>
        </select>
        <select name="is_active" class="select py-2 text-sm w-full sm:w-40">
            <option value="">All Status</option>
            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="btn-primary btn-sm whitespace-nowrap">
            <i data-lucide="filter" class="w-4 h-4"></i> Filter
        </button>
        @if(request()->hasAny(['search','role','is_active']))
        <a href="{{ route('admin.users.index') }}" class="btn-outline btn-sm whitespace-nowrap">
            <i data-lucide="x" class="w-4 h-4"></i> Clear
        </a>
        @endif
    </form>
</div>

{{-- Users Table --}}
<div class="card">
    <div class="table-container rounded-2xl">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>District</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors" x-data="{ deleteModal: false }">
                    <td class="px-4 py-3 text-xs text-gray-400">{{ $user->id }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-700 font-bold text-xs flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                @if($user->donorProfile)
                                <p class="text-xs text-green-600">Has donor profile</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        @if($user->role === 'admin')
                            <span class="badge-red">Admin</span>
                        @elseif($user->role === 'donor')
                            <span class="badge-green">Donor</span>
                        @else
                            <span class="badge-gray">User</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $user->district ?? '—' }}</td>
                    <td class="px-4 py-3">
                        @if($user->is_active)
                            <span class="badge-green">Active</span>
                        @else
                            <span class="badge-gray">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="View">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="p-1.5 text-gray-600 hover:bg-gray-100 rounded-lg transition-all" title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            {{-- Toggle Active --}}
                            <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                                @csrf
                                <button type="submit"
                                    class="p-1.5 {{ $user->is_active ? 'text-yellow-600 hover:bg-yellow-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg transition-all"
                                    title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i data-lucide="{{ $user->is_active ? 'user-x' : 'user-check' }}" class="w-4 h-4"></i>
                                </button>
                            </form>
                            {{-- Delete --}}
                            <button @click="deleteModal = true"
                                class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>

                        {{-- Delete Confirmation Modal --}}
                        <div x-show="deleteModal" x-transition
                             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
                             style="display:none;">
                            <div @click.outside="deleteModal = false" class="card max-w-md w-full p-6 shadow-2xl">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                        <i data-lucide="trash-2" class="w-5 h-5 text-red-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900">Delete User</h3>
                                        <p class="text-sm text-gray-500">This action cannot be undone.</p>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-700 mb-6">
                                    Are you sure you want to delete <strong>{{ $user->name }}</strong>?
                                    All their data will be permanently removed.
                                </p>
                                <div class="flex justify-end gap-3">
                                    <button @click="deleteModal = false" class="btn-outline btn-sm">Cancel</button>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger btn-sm">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center">
                        <i data-lucide="users" class="w-10 h-10 mx-auto mb-3 text-gray-300"></i>
                        <p class="text-gray-400 text-sm">No users found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $users->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection
