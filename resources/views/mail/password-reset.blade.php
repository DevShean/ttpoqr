@component('mail::message')
Hello {{ $userName }},

We received a request to reset your password. Click the button below to reset your password:

@component('mail::button', ['url' => $resetUrl])
Reset Password
@endcomponent

This password reset link will expire in 60 minutes.

If you did not request a password reset, you can ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
