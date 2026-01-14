<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"
        />
        @vite('resources/css/app.css')
        <title>
            Parole & Probation Office
        </title>
    </head>
    
    <body class="bg-gray-50 text-gray-800">
        <!-- Header -->
        <header class="w-full bg-[#4a0000] shadow-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="/assets/img/paplogo.png" alt="PAP Logo" class="w-12 h-12" />
                    <span class="text-xl font-semibold text-white">
                        Parole & Probation Office
                    </span>
                </div>
                <button id="loginBtn" class="px-4 py-2 bg-white text-[#4a0000] font-semibold rounded-lg hover:bg-gray-200">
                    Login
                </button>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-[#6a0000] to-[#4a0000] text-white py-28">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-6">
                    Supporting Rehabilitation & Community Safety
                </h1>
                <p class="text-lg md:text-2xl max-w-2xl mx-auto opacity-90">
                    Our mission is to guide individuals toward positive transformation and
                    help maintain a safer community for all.
                </p>
                <div class="mt-10 flex justify-center gap-4">
                    <a href="#signup" class="relative px-8 py-3 rounded-xl font-semibold text-black bg-gradient-to-r from-[#d4af37] to-[#b68c1a] border border-yellow-200/60 shadow-xl overflow-hidden hover:scale-105 hover:shadow-2xl transition-all duration-300">
                        <span class="relative z-10">
                            GENERATE YOUR QR
                        </span>
                        <!-- Soft gold gloss -->
                        <span class="absolute inset-0 bg-gradient-to-t from-transparent to-white/30 opacity-0 hover:opacity-100 transition-opacity duration-300">
                        </span>
                        <!-- Animated gold shine -->
                        <span class="absolute -left-16 top-0 h-full w-16 bg-white/60 transform -skew-x-12 blur-md animate-[shine_1.5s_infinite]">
                        </span>
                    </a>
                    <style>
                        @keyframes shine { 0% { left: -20%; } 100% { left: 120%; } }
                    </style>
                </div>
            </div>
        </section>
        <!-- Services Section -->
        <section id="services" class="py-24">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-3xl font-bold text-center mb-12">
                    Our Services
                </h2>
                <div class="grid md:grid-cols-3 gap-10">
                    <!-- Card 1 -->
                    <div class="bg-white p-8 rounded-2xl shadow hover:shadow-xl transition">
                        <h3 class="text-xl font-semibold mb-4">
                            Parole Supervision
                        </h3>
                        <p>
                            Monitoring and supporting individuals transitioning back into the community.
                        </p>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-white p-8 rounded-2xl shadow hover:shadow-xl transition">
                        <h3 class="text-xl font-semibold mb-4">
                            Probation Assistance
                        </h3>
                        <p>
                            Helping probationers comply with court requirements and rehabilitation
                            programs.
                        </p>
                    </div>
                    <!-- Card 3 -->
                    <div class="bg-white p-8 rounded-2xl shadow hover:shadow-xl transition">
                        <h3 class="text-xl font-semibold mb-4">
                            Community Outreach
                        </h3>
                        <p>
                            Engaging with local organizations to promote safety and reintegration.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Sign Up Section -->
        <section id="signup" class="py-24 bg-gray-50">
            <div class="max-w-5xl mx-auto px-6">
                <div class="max-w-md mx-auto bg-white p-10 rounded-2xl shadow-lg">
                    
                    <h2 class="text-3xl font-bold text-gray-900 mb-3 text-center">
                        Create an Account
                    </h2>

                    <p class="mb-8 text-gray-600 text-center">
                        Register to access your account and stay updated.
                    </p>

                    <div id="signupMessage">
                        @if(session('success'))
                            <div class="mb-4 text-sm text-green-700 bg-green-100 p-3 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">
                                <ul class="list-disc pl-5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <form id="signupForm" method="POST" action="{{ route('signup') }}" class="grid gap-6">
                        @csrf
                        
                        <!-- Email -->
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5H4.5A2.25 2.25 0 0 0 2.25 6.75m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-6.75 4.05a2.25 2.25 0 0 1-2.31 0l-6.75-4.05a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>

                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" 
                                class="border border-gray-300 p-3 pl-11 w-full rounded-xl focus:outline-none 
                                focus:ring-2 focus:ring-[#6a0000]">
                        </div>

                        <!-- Password -->
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12v6a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 18v-6a2.25 2.25 0 0 1 2.25-2.25z" />
                            </svg>

                            <input type="password" id="password" name="password" placeholder="Password"
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

                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password"
                                class="border border-gray-300 p-3 pl-11 w-full rounded-xl focus:outline-none 
                                focus:ring-2 focus:ring-[#6a0000]">
                        </div>

                        <!-- Password Strength Meter -->
                        <div class="space-y-2">
                            <div class="flex gap-1 h-2">
                                <div id="strengthBar1" class="flex-1 bg-gray-300 rounded transition-all"></div>
                                <div id="strengthBar2" class="flex-1 bg-gray-300 rounded transition-all"></div>
                                <div id="strengthBar3" class="flex-1 bg-gray-300 rounded transition-all"></div>
                                <div id="strengthBar4" class="flex-1 bg-gray-300 rounded transition-all"></div>
                            </div>
                            <p id="strengthText" class="text-xs text-gray-500">Password strength: -</p>
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
                            Sign Up
                        </button>

                    </form>

                    <script>
                        (function(){
                            const signupForm = document.getElementById('signupForm');
                            const signupMessage = document.getElementById('signupMessage');
                            const passwordInput = document.getElementById('password');
                            const confirmPasswordInput = document.getElementById('password_confirmation');
                            
                            if (!signupForm) return;

                            // Password validation functions
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

                                // Update requirement indicators
                                const lengthReq = document.getElementById('req-length');
                                const uppercaseReq = document.getElementById('req-uppercase');
                                const symbolReq = document.getElementById('req-symbol');

                                updateRequirement('req-length', validation.hasLength);
                                updateRequirement('req-uppercase', validation.hasUppercase);
                                updateRequirement('req-symbol', validation.hasSymbol);

                                // Update password strength
                                updatePasswordStrength(validation);

                                // Check password match
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

                            const updatePasswordStrength = (validation) => {
                                let strength = 0;
                                if (passwordInput.value.length > 0) {
                                    if (validation.hasLength) strength++;
                                    if (validation.hasUppercase) strength++;
                                    if (validation.hasSymbol) strength++;
                                    if (passwordInput.value.length >= 12) strength++;
                                }

                                const bars = ['strengthBar1', 'strengthBar2', 'strengthBar3', 'strengthBar4'];
                                const colors = ['bg-red-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
                                const strengthLabels = ['Weak', 'Fair', 'Good', 'Strong'];

                                bars.forEach((barId, index) => {
                                    const bar = document.getElementById(barId);
                                    if (index < strength) {
                                        bar.className = `flex-1 ${colors[index]} rounded transition-all`;
                                    } else {
                                        bar.className = 'flex-1 bg-gray-300 rounded transition-all';
                                    }
                                });

                                const strengthText = document.getElementById('strengthText');
                                if (strength === 0) {
                                    strengthText.textContent = 'Password strength: -';
                                } else {
                                    strengthText.textContent = `Password strength: ${strengthLabels[strength - 1]}`;
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

                            // Event listeners
                            passwordInput.addEventListener('input', updatePasswordRequirements);
                            confirmPasswordInput.addEventListener('input', updatePasswordMatch);

                            signupForm.addEventListener('submit', async function(e){
                                e.preventDefault();
                                signupMessage.innerHTML = '';

                                // Validate password before submission
                                const password = passwordInput.value;
                                const confirmPassword = confirmPasswordInput.value;
                                const validation = validatePassword(password);

                                if (!validation.hasLength || !validation.hasUppercase || !validation.hasSymbol) {
                                    signupMessage.innerHTML = `<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">
                                        <ul class="list-disc pl-5">
                                            ${!validation.hasLength ? '<li>Password must be at least 8 characters long</li>' : ''}
                                            ${!validation.hasUppercase ? '<li>Password must contain at least one uppercase letter</li>' : ''}
                                            ${!validation.hasSymbol ? '<li>Password must contain at least one symbol (!@#$%^&* etc.)</li>' : ''}
                                        </ul>
                                    </div>`;
                                    return;
                                }

                                if (password !== confirmPassword) {
                                    signupMessage.innerHTML = `<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">Passwords do not match</div>`;
                                    return;
                                }

                                const formData = new FormData(signupForm);
                                const payload = {};
                                formData.forEach((v,k)=> payload[k]=v);

                                try {
                                    const res = await fetch(signupForm.action, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        },
                                        body: JSON.stringify(payload),
                                    });

                                    if (res.ok) {
                                        const json = await res.json();
                                        signupMessage.innerHTML = `<div class="mb-4 text-sm text-green-700 bg-green-100 p-3 rounded">${json.message}</div>`;
                                        signupForm.reset();
                                        document.getElementById('passwordMatch').classList.add('hidden');
                                    } else if (res.status === 422) {
                                        const json = await res.json();
                                        const errors = json.errors || {};
                                        let list = '<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded"><ul class="list-disc pl-5">';
                                        for (const key in errors) {
                                            errors[key].forEach(msg => { list += `<li>${msg}</li>`; });
                                        }
                                        list += '</ul></div>';
                                        signupMessage.innerHTML = list;
                                    } else {
                                        const text = await res.text();
                                        signupMessage.innerHTML = `<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">An error occurred (${res.status}).</div>`;
                                        console.error('Signup error', res.status, text);
                                    }
                                } catch(err) {
                                    signupMessage.innerHTML = `<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">Network error. Please try again.</div>`;
                                    console.error(err);
                                }
                            });
                        })();
                    </script>

                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-8 text-center">
            <p>
                &copy; 2025 Parole & Probation Office. All rights reserved.
            </p>
        </footer>

        <!-- Animated Login Modal -->
        <div id="loginModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden flex items-center justify-center z-50 opacity-0 transition-opacity duration-300">
            <div id="modalContent" class="relative w-96 bg-white/80 backdrop-blur-lg rounded-2xl shadow-2xl p-8 transform scale-95 opacity-0 transition-all duration-300">

                <!-- Login Form -->
                <div id="loginForm" class="transition-opacity duration-300 opacity-100">
                    <button id="closeModal" class="absolute top-4 right-4 text-gray-600 hover:text-black font-bold text-xl">&times;</button>
                    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
                    <form id="loginFormModal" class="flex flex-col gap-4" method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Login Message -->
                        <div id="loginMessage"></div>

                        <!-- Email Field -->
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" 
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                    d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5H4.5A2.25 2.25 0 0 0 2.25 6.75m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-6.75 4.05a2.25 2.25 0 0 1-2.31 0l-6.75-4.05a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                            </svg>

                            <input type="email" name="email" placeholder="Email" required
                                class="border p-3 pl-10 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a0000]">
                        </div>

                        <!-- Password Field -->
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" 
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12v6a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 18v-6a2.25 2.25 0 0 1 2.25-2.25z"/>
                            </svg>

                            <input type="password" name="password" placeholder="Password" required
                                class="border p-3 pl-10 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a0000]">
                        </div>

                        <button type="submit"
                            class="bg-[#4a0000] text-white py-3 rounded-lg font-semibold hover:bg-[#6a0000] transition">
                            Login
                        </button>
                    </form>

                    <button id="showForgot" class="mt-4 text-sm text-[#4a0000] hover:underline w-full text-center">Forgot Password?</button>
                </div>

                <!-- Forgot Password Form -->
                <div id="forgotForm" class="transition-opacity duration-300 opacity-0 hidden">
                    <button id="closeModal2" class="absolute top-4 right-4 text-gray-600 hover:text-black font-bold text-xl">&times;</button>
                    <h2 class="text-2xl font-bold mb-6 text-center">Recover Account</h2>
                    
                    <!-- Forgot Password Message -->
                    <div id="forgotMessage"></div>

                    <form id="forgotPasswordForm" class="flex flex-col gap-4" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" 
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                    d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5H4.5A2.25 2.25 0 0 0 2.25 6.75m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-6.75 4.05a2.25 2.25 0 0 1-2.31 0l-6.75-4.05a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                            </svg>

                            <input type="email" name="email" placeholder="Email" required
                                class="border p-3 pl-10 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4a0000]">
                        </div>
                        <button type="submit" class="bg-[#4a0000] text-white py-3 rounded-lg font-semibold hover:bg-[#6a0000] transition">Send Reset Link</button>
                    </form>
                    <button id="backToLogin" class="mt-4 text-sm text-[#4a0000] hover:underline w-full text-center">Back to Login</button>
                </div>

            </div>
        </div>


        <script>
            const loginBtn = document.getElementById('loginBtn');
            const loginModal = document.getElementById('loginModal');
            const modalContent = document.getElementById('modalContent');

            const loginForm = document.getElementById('loginForm');
            const forgotForm = document.getElementById('forgotForm');

            const closeModal = document.getElementById('closeModal');
            const closeModal2 = document.getElementById('closeModal2');
            const showForgot = document.getElementById('showForgot');
            const backToLogin = document.getElementById('backToLogin');

            // Smooth open
            function openModal() {
                loginModal.classList.remove('hidden');
                setTimeout(() => {
                    loginModal.classList.add('opacity-100');
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            // Smooth close
            function closeModalAnimated() {
                loginModal.classList.remove('opacity-100');
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');

                setTimeout(() => {
                    loginModal.classList.add('hidden');
                }, 300);
            }

            loginBtn.addEventListener('click', openModal);
            closeModal.addEventListener('click', closeModalAnimated);
            closeModal2.addEventListener('click', closeModalAnimated);

            // Switch to Forgot Form
            showForgot.addEventListener('click', () => {
                loginForm.classList.add('opacity-0');
                setTimeout(() => {
                    loginForm.classList.add('hidden');
                    forgotForm.classList.remove('hidden');
                    forgotForm.classList.add('opacity-100');
                }, 300);
            });

            // Back to Login Form
            backToLogin.addEventListener('click', () => {
                forgotForm.classList.add('opacity-0');
                setTimeout(() => {
                    forgotForm.classList.add('hidden');
                    loginForm.classList.remove('hidden');
                    loginForm.classList.add('opacity-100');
                }, 300);
            });

            // Close clicking outside modal
            window.addEventListener('click', (e) => {
                if (e.target === loginModal) closeModalAnimated();
            });

            // Prevent page auto-scrolling to #contact after refresh
            if (window.location.hash === "#signup") {
                history.replaceState(null, null, " ");
            }
            window.scrollTo({ top: 0, behavior: "instant" });

            // Login form handler
            (function(){
                const loginFormModal = document.getElementById('loginFormModal');
                const loginMessage = document.getElementById('loginMessage');
                if (!loginFormModal) return;

                loginFormModal.addEventListener('submit', async function(e){
                    e.preventDefault();
                    loginMessage.innerHTML = '';
                    
                    const submitBtn = loginFormModal.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerText;
                    submitBtn.disabled = true;
                    submitBtn.innerText = 'Signing in...';

                    const formData = new FormData(loginFormModal);
                    const payload = {};
                    formData.forEach((v,k)=> payload[k]=v);

                    try {
                        const res = await fetch(loginFormModal.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify(payload),
                        });

                        if (res.ok) {
                            const json = await res.json();
                            loginMessage.innerHTML = `<div class="mb-4 text-sm text-green-700 bg-green-100 p-3 rounded">Login successful. Redirecting...</div>`;
                            setTimeout(() => {
                                window.location.href = json.redirect || '/';
                            }, 1000);
                        } else if (res.status === 401) {
                            loginMessage.innerHTML = `<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">Invalid email or password. Please try again.</div>`;
                        } else if (res.status === 422) {
                            const json = await res.json();
                            const errors = json.errors || {};
                            let list = '<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded"><ul class="list-disc pl-5">';
                            for (const key in errors) {
                                errors[key].forEach(msg => { list += `<li>${msg}</li>`; });
                            }
                            list += '</ul></div>';
                            loginMessage.innerHTML = list;
                        } else {
                            const text = await res.text();
                            console.error('Login error', res.status, text);
                            loginMessage.innerHTML = `<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">An error occurred (${res.status}). Please try again later.</div>`;
                        }
                    } catch(err) {
                        console.error(err);
                        loginMessage.innerHTML = `<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">Network error. Please check your connection and try again.</div>`;
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerText = originalText;
                    }
                });
            })();

            // Forgot password form handler
            (function(){
                const forgotPasswordForm = document.getElementById('forgotPasswordForm');
                const forgotMessage = document.getElementById('forgotMessage');
                if (!forgotPasswordForm) return;

                forgotPasswordForm.addEventListener('submit', async function(e){
                    e.preventDefault();
                    forgotMessage.innerHTML = '';
                    
                    const submitBtn = forgotPasswordForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerText;
                    submitBtn.disabled = true;
                    submitBtn.innerText = 'Sending...';

                    const formData = new FormData(forgotPasswordForm);
                    const payload = {};
                    formData.forEach((v,k)=> payload[k]=v);

                    try {
                        const res = await fetch(forgotPasswordForm.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify(payload),
                        });

                        if (res.ok) {
                            const json = await res.json();
                            forgotMessage.innerHTML = `<div class="mb-4 text-sm text-green-700 bg-green-100 p-3 rounded">Success! Check your email for the password reset link.</div>`;
                            forgotPasswordForm.reset();
                            setTimeout(() => {
                                closeModalAnimated();
                            }, 2000);
                        } else if (res.status === 404) {
                            forgotMessage.innerHTML = `<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">No account found with this email address.</div>`;
                        } else if (res.status === 422) {
                            const json = await res.json();
                            const errors = json.errors || {};
                            let list = '<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded"><ul class="list-disc pl-5">';
                            for (const key in errors) {
                                errors[key].forEach(msg => { list += `<li>${msg}</li>`; });
                            }
                            list += '</ul></div>';
                            forgotMessage.innerHTML = list;
                        } else {
                            const text = await res.text();
                            console.error('Forgot password error', res.status, text);
                            forgotMessage.innerHTML = `<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">An error occurred. Please try again later.</div>`;
                        }
                    } catch(err) {
                        console.error(err);
                        forgotMessage.innerHTML = `<div class="mb-4 text-sm text-red-700 bg-red-100 p-3 rounded">Network error. Please check your connection and try again.</div>`;
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerText = originalText;
                    }
                });
            })();
        </script>



    </body>

</html>