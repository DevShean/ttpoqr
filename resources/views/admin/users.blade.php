@extends('admin_layout.app')

@section('title', 'Users')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">User Management</h1>
            <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-600">View and manage registered users</p>
        </div>
    </div>

    <!-- User Stats -->
    <div class="grid grid-cols-2 md:grid-cols-2 gap-2 sm:gap-4 mb-6 sm:mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-lg sm:text-3xl font-bold text-blue-600 mt-1 sm:mt-2">{{ $users->total() }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-blue-50 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-users text-lg sm:text-2xl text-blue-500"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Regular Users</p>
                    <p class="text-lg sm:text-3xl font-bold text-green-600 mt-1 sm:mt-2">{{ $users->where('usertype_id', 2)->count() }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-green-50 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-user text-lg sm:text-2xl text-green-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 shadow-sm mb-8">
        <form method="GET" action="{{ route('admin.users') }}" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="sm:col-span-2 lg:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search User</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ $currentFilters['search'] }}"
                           placeholder="Name or email..."
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Sort</label>
                    <select id="sort" 
                            name="sort"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="latest" {{ $currentFilters['sort'] === 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="oldest" {{ $currentFilters['sort'] === 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="sm:col-span-2 lg:col-span-2 flex flex-col sm:flex-row items-stretch sm:items-end gap-2">
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition active:scale-[0.98]">
                        <i class="fi fi-rr-search mr-2"></i><span class="hidden sm:inline">Filter</span>
                    </button>
                    <a href="{{ route('admin.users') }}" 
                       class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition text-center">
                        <i class="fi fi-rr-refresh mr-2"></i><span class="hidden sm:inline">Reset</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        @if($users->count() > 0)
            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <table class="w-full min-w-max sm:min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 sm:px-6 py-4 text-left text-sm font-semibold text-gray-900">User</th>
                            <th class="hidden sm:table-cell px-4 sm:px-6 py-4 text-left text-sm font-semibold text-gray-900">Email</th>
                            <th class="px-4 sm:px-6 py-4 text-left text-sm font-semibold text-gray-900">Role</th>
                            <th class="hidden lg:table-cell px-4 sm:px-6 py-4 text-left text-sm font-semibold text-gray-900">Joined</th>
                            <th class="px-4 sm:px-6 py-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 sm:px-6 py-4">
                                    <div class="flex items-center gap-2 sm:gap-3">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-500 flex items-center justify-center text-white font-bold text-xs sm:text-sm flex-shrink-0">
                                            {{ substr($user->profile->fname ?? $user->email, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-medium text-gray-900 text-sm truncate">
                                                @if($user->profile)
                                                    {{ $user->profile->fname }} {{ $user->profile->mname }} {{ $user->profile->lname }}
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500 truncate sm:hidden">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell px-4 sm:px-6 py-4">
                                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    @if($user->usertype_id === 1)
                                        <span class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-3 py-1 bg-purple-50 border border-purple-200 text-purple-700 text-xs sm:text-sm font-medium rounded-full">
                                            <i class="fi fi-rr-shield-admin text-xs hidden sm:inline"></i>
                                            <span>Admin</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-3 py-1 bg-green-50 border border-green-200 text-green-700 text-xs sm:text-sm font-medium rounded-full">
                                            <i class="fi fi-rr-user text-xs hidden sm:inline"></i>
                                            <span>User</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="hidden lg:table-cell px-4 sm:px-6 py-4">
                                    <div class="text-sm text-gray-600">
                                        <span class="block">{{ $user->created_at->format('M d, Y') }}</span>
                                        <span class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    <div class="flex items-center gap-1 sm:gap-2 flex-wrap justify-end sm:justify-start">
                                        @if($user->id !== Auth::id())
                                            <button onclick="window.deleteUser({{ $user->id }}, '{{ addslashes($user->profile->fname ?? 'User') }} {{ addslashes($user->profile->lname ?? '') }}')"
                                                    class="px-2 sm:px-3 py-1 bg-red-50 text-red-700 border border-red-200 text-xs sm:text-sm font-medium rounded-lg hover:bg-red-100 transition active:scale-95 whitespace-nowrap">
                                                <i class="fi fi-rr-trash mr-0 sm:mr-1"></i><span class="hidden sm:inline">Delete</span>
                                            </button>
                                        @else
                                            <span class="text-xs sm:text-sm text-gray-500 italic">Current User</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @else
            <div class="px-4 sm:px-6 py-12 text-center">
                <div class="mb-4">
                    <i class="fi fi-rr-users text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Users Found</h3>
                <p class="text-gray-600">There are no users matching your filters.</p>
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