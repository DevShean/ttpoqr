@extends('admin_layout.app')

@section('title', 'Attendance Forms')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 py-3 sm:py-6 md:py-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-6 md:mb-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Attendance Forms</h1>
                <p class="mt-1 text-xs sm:text-sm md:text-base text-gray-600">Create and manage attendance records</p>
            </div>
            <a href="{{ route('admin.attendance_forms.create') }}"
                    class="px-4 py-2.5 bg-blue-600 text-white font-medium text-sm sm:text-base rounded-lg hover:bg-blue-700 transition active:scale-95 inline-block text-center w-full sm:w-auto">
                <i class="fi fi-rr-plus mr-2"></i><span>New Form</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 sm:mb-6 p-3 sm:p-4 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 shadow-sm">
            <div class="flex items-center gap-2 sm:gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-green-700 font-medium text-sm sm:text-base">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Forms Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        @if($forms->count() > 0)
            <!-- Mobile Card View and Desktop Table -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">Date</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">Venue</th>
                            <th class="hidden md:table-cell px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">Activities</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">Attendees</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($forms as $form)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <p class="text-xs md:text-sm font-medium text-gray-900">{{ $form->date->format('M d, Y') }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <p class="text-xs md:text-sm text-gray-600">{{ $form->venue }}</p>
                                </td>
                                <td class="hidden md:table-cell px-4 md:px-6 py-3 md:py-4">
                                    <p class="text-xs md:text-sm text-gray-600 truncate">{{ Str::limit($form->activities_conducted, 50) }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $form->records()->count() }}
                                    </span>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('admin.attendance_forms.show', $form) }}"
                                                class="px-2.5 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-medium rounded-lg hover:bg-blue-100 transition">
                                            <i class="fi fi-rr-eye"></i><span class="hidden md:inline ml-1">View</span>
                                        </a>
                                        <button onclick="deleteForm({{ $form->id }})"
                                                class="px-2.5 py-1.5 bg-red-50 text-red-700 border border-red-200 text-xs font-medium rounded-lg hover:bg-red-100 transition">
                                            <i class="fi fi-rr-trash"></i><span class="hidden md:inline ml-1">Delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="sm:hidden divide-y divide-gray-200">
                @foreach($forms as $form)
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Date</p>
                                <p class="text-sm font-medium text-gray-900">{{ $form->date->format('M d, Y') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 whitespace-nowrap">
                                {{ $form->records()->count() }} recorded
                            </span>
                        </div>
                        
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Venue</p>
                            <p class="text-sm text-gray-900 font-medium">{{ $form->venue }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Activities</p>
                            <p class="text-sm text-gray-600">{{ Str::limit($form->activities_conducted, 80) }}</p>
                        </div>

                        <div class="flex gap-2 pt-2">
                            <a href="{{ route('admin.attendance_forms.show', $form) }}"
                                    class="flex-1 px-3 py-2 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-medium rounded-lg hover:bg-blue-100 transition text-center">
                                <i class="fi fi-rr-eye mr-1"></i>View
                            </a>
                            <button onclick="deleteForm({{ $form->id }})"
                                    class="flex-1 px-3 py-2 bg-red-50 text-red-700 border border-red-200 text-xs font-medium rounded-lg hover:bg-red-100 transition">
                                <i class="fi fi-rr-trash mr-1"></i>Delete
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                <div class="mb-4">
                    <i class="fi fi-rr-clipboard text-5xl sm:text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No Attendance Forms</h3>
                <p class="text-sm sm:text-base text-gray-600">Create a new attendance form to get started.</p>
            </div>
        @endif
    </div>
</div>

<script>
    function deleteForm(formId) {
        Swal.fire({
            title: 'Delete Form?',
            text: 'This will delete the form and all associated attendance records.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
        }).then(result => {
            if (result.isConfirmed) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/admin/attendance-forms/${formId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(() => {
                    window.location.reload();
                });
            }
        });
    }
</script>

@endsection
