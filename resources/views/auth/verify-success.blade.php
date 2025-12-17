@extends('layouts.app')

@section('title', 'Email Verified')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <!-- Hidden content, SweetAlert will show instead -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Email Verified!',
                text: 'Your email has been successfully verified. You can now login to your account.',
                icon: 'success',
                confirmButtonText: 'Go to Login',
                confirmButtonColor: '#3B82F6',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/';
                }
            });
        });
    </script>
@endsection
