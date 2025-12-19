@extends('admin_layout.app')

@section('title', 'Users')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 py-3 sm:py-6 md:py-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-6 md:mb-8">
        <div>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">User Management</h1>
            <p class="mt-1 text-xs sm:text-sm md:text-base text-gray-600">View and manage registered users</p>
        </div>
    </div>

    <!-- User Stats -->
    <div class="grid grid-cols-2 gap-2 sm:gap-4 mb-4 sm:mb-6 md:mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 md:p-6 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-base sm:text-2xl md:text-3xl font-bold text-blue-600 mt-1">{{ $users->total() }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-blue-50 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-users text-base sm:text-lg md:text-2xl text-blue-500"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 md:p-6 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Regular Users</p>
                    <p class="text-base sm:text-2xl md:text-3xl font-bold text-green-600 mt-1">{{ $users->where('usertype_id', 2)->count() }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-green-50 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-user text-base sm:text-lg md:text-2xl text-green-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 md:p-6 shadow-sm mb-4 sm:mb-6 md:mb-8">
        <form method="GET" action="{{ route('admin.users') }}" class="space-y-4 sm:space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <!-- Search -->
                <div class="sm:col-span-2 lg:col-span-2">
                    <label for="search" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Search User</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ $currentFilters['search'] }}"
                           placeholder="Name or email..."
                           class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Sort</label>
                    <select id="sort" 
                            name="sort"
                            class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="latest" {{ $currentFilters['sort'] === 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="oldest" {{ $currentFilters['sort'] === 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="sm:col-span-2 lg:col-span-2 flex flex-col sm:flex-row items-stretch sm:items-end gap-2">
                    <button type="submit" 
                            class="flex-1 px-3 sm:px-4 py-2.5 sm:py-2 bg-blue-600 text-white text-sm sm:text-base font-medium rounded-lg hover:bg-blue-700 transition active:scale-[0.98]">
                        <i class="fi fi-rr-search mr-2"></i><span>Filter</span>
                    </button>
                    <a href="{{ route('admin.users') }}" 
                       class="flex-1 px-3 sm:px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 text-sm sm:text-base font-medium rounded-lg hover:bg-gray-200 transition text-center">
                        <i class="fi fi-rr-refresh mr-2"></i><span>Reset</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        @if($users->count() > 0)
            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">User</th>
                            <th class="hidden sm:table-cell px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">Email</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">Role</th>
                            <th class="hidden lg:table-cell px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">Joined</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <div class="flex items-center gap-2 md:gap-3">
                                        @if($user->profile && $user->profile->avatar_path)
                                            <img src="{{ asset('storage/' . $user->profile->avatar_path) }}" 
                                                 alt="{{ $user->profile->fname ?? $user->email }}" 
                                                 class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover flex-shrink-0 border border-gray-200">
                                        @else
                                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-500 flex items-center justify-center text-white font-bold text-xs md:text-sm flex-shrink-0">
                                                {{ substr($user->profile->fname ?? $user->email, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-medium text-gray-900 text-xs md:text-sm truncate">
                                                @if($user->profile)
                                                    {{ $user->profile->fname }} {{ $user->profile->mname }} {{ $user->profile->lname }}
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell px-4 md:px-6 py-3 md:py-4">
                                    <p class="text-xs md:text-sm text-gray-600">{{ $user->email }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    @if($user->usertype_id === 1)
                                        <span class="inline-flex items-center gap-1 px-2 md:px-3 py-1 bg-purple-50 border border-purple-200 text-purple-700 text-xs font-medium rounded-full">
                                            <i class="fi fi-rr-shield-admin text-xs hidden md:inline"></i>
                                            <span>Admin</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 md:px-3 py-1 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded-full">
                                            <i class="fi fi-rr-user text-xs hidden md:inline"></i>
                                            <span>User</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="hidden lg:table-cell px-4 md:px-6 py-3 md:py-4">
                                    <div class="text-xs md:text-sm text-gray-600">
                                        <span class="block">{{ $user->created_at->format('M d, Y') }}</span>
                                        <span class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <div class="flex items-center gap-1.5">
                                        @if($user->id !== Auth::id())
                                            <button onclick="window.deleteUser({{ $user->id }}, '{{ addslashes($user->profile->fname ?? 'User') }} {{ addslashes($user->profile->lname ?? '') }}')"
                                                    class="px-2.5 py-1.5 bg-red-50 text-red-700 border border-red-200 text-xs font-medium rounded-lg hover:bg-red-100 transition active:scale-95">
                                                <i class="fi fi-rr-trash"></i><span class="hidden md:inline ml-1">Delete</span>
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-500 italic">Current</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="sm:hidden divide-y divide-gray-200">
                @foreach($users as $user)
                    <div class="p-4 space-y-3">
                        <div class="flex items-center gap-3">
                            @if($user->profile && $user->profile->avatar_path)
                                <img src="{{ asset('storage/' . $user->profile->avatar_path) }}" 
                                     alt="{{ $user->profile->fname ?? $user->email }}" 
                                     class="w-10 h-10 rounded-full object-cover flex-shrink-0 border border-gray-200">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                    {{ substr($user->profile->fname ?? $user->email, 0, 1) }}
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 text-sm truncate">
                                    @if($user->profile)
                                        {{ $user->profile->fname }} {{ $user->profile->lname }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Role</p>
                                @if($user->usertype_id === 1)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-50 border border-purple-200 text-purple-700 text-xs font-medium rounded-full mt-1">
                                        <i class="fi fi-rr-shield-admin text-xs"></i>
                                        <span>Admin</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded-full mt-1">
                                        <i class="fi fi-rr-user text-xs"></i>
                                        <span>User</span>
                                    </span>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Joined</p>
                                <p class="text-xs text-gray-600 font-medium mt-1">{{ $user->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        @if($user->id !== Auth::id())
                            <button onclick="window.deleteUser({{ $user->id }}, '{{ addslashes($user->profile->fname ?? 'User') }} {{ addslashes($user->profile->lname ?? '') }}')"
                                    class="w-full px-3 py-2.5 bg-red-50 text-red-700 border border-red-200 text-xs font-medium rounded-lg hover:bg-red-100 transition">
                                <i class="fi fi-rr-trash mr-2"></i>Delete Account
                            </button>
                        @else
                            <div class="w-full px-3 py-2.5 bg-gray-50 text-gray-600 border border-gray-200 text-xs font-medium rounded-lg text-center italic">
                                Current User
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-4 sm:px-6 py-4 sm:py-6 border-t border-gray-200 overflow-x-auto">
                <style>
                    .pagination { 
                        display: flex; 
                        justify-content: center; 
                        gap: 0.25rem;
                        flex-wrap: wrap;
                    }
                    .pagination a, 
                    .pagination span {
                        padding: 0.5rem 0.75rem;
                        font-size: 0.875rem;
                        border: 1px solid #e5e7eb;
                        border-radius: 0.5rem;
                        text-decoration: none;
                        transition: all 0.2s;
                    }
                    .pagination a:hover {
                        background-color: #eff6ff;
                        border-color: #3b82f6;
                        color: #3b82f6;
                    }
                    .pagination span.active {
                        background-color: #3b82f6;
                        color: white;
                        border-color: #3b82f6;
                    }
                    .pagination span:disabled,
                    .pagination span.disabled {
                        color: #9ca3af;
                        cursor: not-allowed;
                    }
                    @media (max-width: 640px) {
                        .pagination a, 
                        .pagination span {
                            padding: 0.375rem 0.5rem;
                            font-size: 0.75rem;
                        }
                    }
                </style>
                {{ $users->links() }}
            </div>
        @else
            <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                <div class="mb-4">
                    <i class="fi fi-rr-users text-5xl sm:text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No Users Found</h3>
                <p class="text-xs sm:text-base text-gray-600">There are no users matching your filters.</p>
            </div>
        @endif
    </div>
</div>

<div x-data="userManager()">
    <!-- Hidden CSRF token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</div>

<script>
    function userManager() {
        return {
            deleteUser(userId, userName) {
                Swal.fire({
                    title: 'Delete User?',
                    html: `<p>Are you sure you want to delete <strong>${userName}'s</strong> account?</p>
                           <p class="text-sm text-red-600 mt-2">⚠️ This action cannot be undone. All associated data will be deleted.</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete User',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submitDelete(userId, userName);
                    }
                });
            },
            submitDelete(userId, userName) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch(`/admin/users/${userId}/delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => {
                    if (!r.ok) return r.json().then(e => Promise.reject(e));
                    return r.json();
                })
                .then(data => {
                    Swal.fire({
                        title: 'Success!',
                        text: `${userName}'s account has been deleted.`,
                        icon: 'success',
                        confirmButtonColor: '#3B82F6'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'Failed to delete user',
                        icon: 'error',
                        confirmButtonColor: '#EF4444'
                    });
                });
            }
        }
    }

    // Make functions globally available
    window.deleteUser = function(userId, userName) {
        return userManager().deleteUser(userId, userName);
    };
</script>

@endsection