<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <title>Reset Password - Parole & Probation Office</title>
</head>
<body class="bg-gray-50 text-gray-800">
    <!-- Header -->
    <header class="w-full bg-[#4a0000] shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="/assets/img/paplogo.png" alt="PAP Logo" class="w-12 h-12" />
                <span class="text-xl font-semibold text-white">
                    Parole & Probation Office
                </span>
            </div>
        </div>
    </header>

    <!-- Reset Password Section -->
    <section class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full bg-white p-10 rounded-2xl shadow-lg">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">
                Reset Your Password
            </h2>

            <div id="resetMessage"></div>

            <form id="resetPasswordForm" method="POST" action="{{ route('password.update') }}" class="grid gap-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email -->
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5H4.5A2.25 2.25 0 0 0 2.25 6.75m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-6.75 4.05a2.25 2.25 0 0 1-2.31 0l-6.75-4.05a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>

                    <input type="email" name="email" placeholder="Email" required
                        class="border border-gray-300 p-3 pl-11 w-full rounded-xl focus:outline-none 
                        focus:ring-2 focus:ring-[#6a0000]">
                </div>

                <!-- New Password -->
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12v6a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 18v-6a2.25 2.25 0 0 1 2.25-2.25z" />
                    </svg>

                    <input type="password" id="password" name="password" placeholder="New Password" required
                        class="border border-gray-300 p-3 pl-11 w-full rounded-xl focus:outline-none 
                        focus:ring-2 focus:ring-[#6a0000]">
                </div>

                <!-- Confirm Password -->
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12v6a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 18v-6a2.25 2.25 0 0 1 2.25-2.25z"/>
                    </svg>

                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required
                        class="border border-gray-300 p-3 pl-11 w-full rounded-xl focus:outline-none 
                        focus:ring-2 focus:ring-[#6a0000]">
                </div>

                <!-- Password Requirements -->
                <div class="bg-gray-100 p-3 rounded-lg space-y-2">
                    <p class="text-xs font-semibold text-gray-700 mb-2">Password Requirements:</p>
                    <div id="req-length" class="flex items-center gap-2 text-xs text-gray-600">
                        <span id="req-length-icon">❌</span>
                        <span>At least 8 characters</span>
                    </div>
                    <div id="req-uppercase" class="flex items-center gap-2 text-xs text-gray-600">
                        <span id="req-uppercase-icon">❌</span>
                        <span>One uppercase letter (A-Z)</span>
                    </div>
                    <div id="req-symbol" class="flex items-center gap-2 text-xs text-gray-600">
                        <span id="req-symbol-icon">❌</span>
                        <span>One symbol (!@#$%^&*)</span>
                    </div>
                </div>

                <!-- Password Match Status -->
                <div id="passwordMatch" class="flex items-center gap-2 text-sm hidden">
                    <span id="matchIcon">❌</span>
                    <span id="matchText">Passwords do not match</span>
                </div>

                <!-- Button -->
                <button type="submit"
                    class="bg-[#6a0000] hover:bg-[#4a0000] text-white py-3 rounded-xl font-semibold 
                    transition-all shadow-md hover:shadow-lg">
                    Reset Password
                </button>

                <a href="/" class="text-center text-sm text-[#4a0000] hover:underline">
                    Back to Home
                </a>
            </form>
        </div>
    </section>

    <script>
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const resetPasswordForm = document.getElementById('resetPasswordForm');
        const resetMessage = document.getElementById('resetMessage');

        const validatePassword = (password) => {
            return {
                hasLength: password.length >= 8,
                hasUppercase: /[A-Z]/.test(password),
                hasSymbol: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
            };
        };

        const updatePasswordRequirements = () => {
            const password = passwordInput.value;
            const validation = validatePassword(password);

            updateRequirement('req-length', validation.hasLength);
            updateRequirement('req-uppercase', validation.hasUppercase);
            updateRequirement('req-symbol', validation.hasSymbol);

            updatePasswordMatch();
        };

        const updateRequirement = (elementId, isMet) => {
            const element = document.getElementById(elementId);
            const icon = element.querySelector('span:first-child');
            if (isMet) {
                icon.textContent = '✅';
                element.classList.remove('text-gray-600');
                element.classList.add('text-green-600');
            } else {
                icon.textContent = '❌';
                element.classList.remove('text-green-600');
                element.classList.add('text-gray-600');
            }
        };

        const updatePasswordMatch = () => {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const matchContainer = document.getElementById('passwordMatch');
            const matchIcon = document.getElementById('matchIcon');
            const matchText = document.getElementById('matchText');

            if (confirmPassword === '') {
                matchContainer.classList.add('hidden');
                return;
            }

            matchContainer.classList.remove('hidden');
            if (password === confirmPassword) {
                matchIcon.textContent = '✅';
                matchText.textContent = 'Passwords match';
                matchContainer.classList.remove('text-red-600');
                matchContainer.classList.add('text-green-600');
            } else {
                matchIcon.textContent = '❌';
                matchText.textContent = 'Passwords do not match';
                matchContainer.classList.remove('text-green-600');
                matchContainer.classList.add('text-red-600');
            }
        };

        passwordInput.addEventListener('input', updatePasswordRequirements);
        confirmPasswordInput.addEventListener('input', updatePasswordMatch);

        resetPasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            resetMessage.innerHTML = '';

            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const validation = validatePassword(password);

            if (!validation.hasLength || !validation.hasUppercase || !validation.hasSymbol) {
                resetMessage.innerHTML = `<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">
                    <ul class="list-disc pl-5">
                        ${!validation.hasLength ? '<li>Password must be at least 8 characters</li>' : ''}
                        ${!validation.hasUppercase ? '<li>Password must contain one uppercase letter</li>' : ''}
                        ${!validation.hasSymbol ? '<li>Password must contain one symbol</li>' : ''}
                    </ul>
                </div>`;
                return;
            }

            if (password !== confirmPassword) {
                resetMessage.innerHTML = `<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">Passwords do not match</div>`;
                return;
            }

            // Submit the form normally
            this.submit();
        });
    </script>
</body>
</html>
