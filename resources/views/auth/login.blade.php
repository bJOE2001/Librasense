<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - LibraSense</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-image: url('https://www.transparenttextures.com/patterns/paper-fibers.png');
        }
        .fade-in {
            animation: fadeIn 0.8s cubic-bezier(.4,0,.2,1);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: none; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 to-white py-12 px-4 sm:px-6 lg:px-8 font-sans antialiased">
    <div class="max-w-md w-full space-y-8 fade-in">
        <div class="flex flex-col items-center">
            <img class="h-16 w-auto mb-4 animate-bounce" src="{{ asset('images/logo.png') }}" alt="LibraSense Logo">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center">Sign in to your account</h2>
            <p class="mt-2 text-base text-gray-500 text-center">Welcome back! Please sign in to continue.</p>
        </div>
        <div class="bg-white py-8 px-6 shadow-2xl border border-gray-100 rounded-2xl relative">
            @if ($errors->any())
                <div x-data="{ open: true }" x-show="open" x-transition class="mb-4 p-4 rounded-md bg-red-50 border border-red-200 flex items-start gap-3 animate-pulse">
                    <svg class="h-5 w-5 text-red-400 mt-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button @click="open = false" class="ml-4 text-gray-400 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            <form class="space-y-6" method="POST" action="{{ route('login') }}" x-data="loginForm()" @submit.prevent="submitForm">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-900">Email address</label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0V8a4 4 0 00-8 0v4m8 0v4a4 4 0 01-8 0v-4"/></svg>
                            </span>
                            <input id="email" name="email" type="email" autocomplete="email" required x-model="email"
                                :class="emailValid === true ? 'ring-2 ring-green-400' : emailValid === false ? 'ring-2 ring-red-400' : 'ring-1 ring-inset ring-gray-300'"
                                @input="validateEmail"
                                class="block w-full rounded-md border-0 py-2 pl-10 pr-10 shadow-sm placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <template x-if="emailValid === true">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </template>
                                <template x-if="emailValid === false">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </template>
                            </span>
                        </div>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-900">Password</label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11V7a4 4 0 10-8 0v4m8 0v4a4 4 0 01-8 0v-4"/></svg>
                            </span>
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password" autocomplete="current-password" required x-model="password"
                                class="block w-full rounded-md border-0 py-2 pl-10 pr-10 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            <button type="button" @click="showPassword = !showPassword" :aria-label="showPassword ? 'Hide password' : 'Show password'"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-primary-600 focus:outline-none">
                                <template x-if="showPassword">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10 0-1.657.336-3.234.938-4.675M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </template>
                                <template x-if="!showPassword">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </template>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                            <label for="remember_me" class="ml-3 block text-sm leading-6 text-gray-700">Remember me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <div class="text-sm leading-6">
                                <a href="{{ route('password.request') }}" class="font-semibold text-primary-600 hover:text-primary-500 transition">
                                    Forgot password?
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="mt-8">
                    <button type="submit" :disabled="loading"
                        class="flex w-full justify-center rounded-lg bg-primary-600 px-4 py-2.5 text-base font-semibold leading-6 text-white shadow-lg hover:bg-primary-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition disabled:opacity-60 disabled:cursor-not-allowed">
                        <template x-if="loading">
                            <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                        </template>
                        <span x-text="loading ? 'Signing in...' : 'Sign in'"></span>
                    </button>
                </div>
            </form>
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-500 transition">
                        Sign up now
                    </a>
                </p>
            </div>
            <div class="mt-8 flex flex-col items-center justify-center gap-2">
                <span class="text-gray-400 text-xs">&copy; {{ date('Y') }} LibraSense</span>
                <div class="flex gap-4 mt-2">
                    <a href="#" class="text-xs text-gray-400 hover:text-primary-600 transition">Terms of Service</a>
                    <a href="#" class="text-xs text-gray-400 hover:text-primary-600 transition">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function loginForm() {
            return {
                email: '',
                password: '',
                showPassword: false,
                loading: false,
                emailValid: null,
                validateEmail() {
                    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    this.emailValid = this.email.length === 0 ? null : re.test(this.email);
                },
                submitForm(e) {
                    this.loading = true;
                    e.target.submit();
                }
            }
        }
    </script>
</body>
</html> 