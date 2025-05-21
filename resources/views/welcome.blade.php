<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>LibraSense - Modern Library Management System</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .hero-bg {
                background: radial-gradient(ellipse at 60% 40%, #e0e7ff 0%, #fff 70%);
            }
            .animated-shape {
                position: absolute;
                top: -60px;
                right: -80px;
                width: 400px;
                height: 400px;
                background: linear-gradient(135deg, #6366f1 0%, #a5b4fc 100%);
                opacity: 0.15;
                filter: blur(60px);
                border-radius: 50%;
                z-index: 0;
                animation: float 8s ease-in-out infinite;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(30px) scale(1.05); }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-white">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur shadow-sm sticky top-0 z-30 transition-all">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" class="flex items-center group">
                            <img src="{{ asset('images/logo.png') }}" alt="LibraSense Logo" class="block h-9 w-auto transition-transform group-hover:scale-105" />
                            <span class="ml-2 text-2xl font-extrabold text-gray-900 tracking-tight group-hover:text-primary-600 transition">LibraSense</span>
                            </a>
                        <div class="hidden sm:flex sm:space-x-8 ml-8">
                            <a href="#about" class="text-gray-700 font-medium hover:text-primary-600 transition">About</a>
                        </div>
                    </div>
                    <div class="hidden sm:flex items-center space-x-3">
                        @auth
                            @if(auth()->user()->email === 'admin@librasense.com')
                                <a href="{{ route('admin.dashboard') }}" class="px-5 py-2 rounded-lg font-semibold text-white bg-primary-600 hover:bg-primary-700 shadow transition">Admin Dashboard</a>
                            @else
                                <a href="{{ route('user.dashboard') }}" class="px-5 py-2 rounded-lg font-semibold text-white bg-primary-600 hover:bg-primary-700 shadow transition">User Dashboard</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2 rounded-lg font-semibold text-primary-600 border border-primary-600 bg-white hover:bg-primary-50 shadow-sm transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2 rounded-lg font-semibold text-white bg-primary-600 hover:bg-primary-700 shadow transition">Register</a>
                            @endif
                        @endauth
                    </div>
                    <!-- Mobile menu button -->
                    <div class="flex items-center sm:hidden">
                        <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile menu -->
            <div class="sm:hidden hidden" id="mobile-menu">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="#about" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">About</a>
                    @auth
                        @if(auth()->user()->email === 'admin@librasense.com')
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">Admin Dashboard</a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">User Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">Register</a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative hero-bg overflow-hidden">
            <div class="animated-shape"></div>
            <div class="max-w-7xl mx-auto flex flex-col-reverse lg:flex-row items-center px-4 sm:px-6 md:px-8 pt-16 pb-20 lg:pt-24 lg:pb-32">
                <div class="w-full lg:w-1/2 z-10">
                    <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold text-gray-900 leading-tight mb-6">
                                <span class="block">Welcome to</span>
                                <span class="block text-primary-600">LibraSense</span>
                            </h1>
                    <p class="text-xl sm:text-2xl text-gray-600 mb-8 max-w-xl">
                        A modern library management system for effortless book tracking, borrowing, and management. Experience the future of library management today.
                            </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                                    @auth
                                        @if(auth()->user()->email === 'admin@librasense.com')
                                <a href="{{ route('admin.dashboard') }}" class="px-8 py-3 rounded-lg font-semibold text-white bg-primary-600 hover:bg-primary-700 shadow-lg text-lg transition">Go to Dashboard</a>
                                        @else
                                <a href="{{ route('user.dashboard') }}" class="px-8 py-3 rounded-lg font-semibold text-white bg-primary-600 hover:bg-primary-700 shadow-lg text-lg transition">Go to Dashboard</a>
                                        @endif
                                    @else
                            <a href="{{ route('register') }}" class="px-8 py-3 rounded-lg font-semibold text-white bg-primary-600 hover:bg-primary-700 shadow-lg text-lg transition">Get Started</a>
                            <a href="#about" class="px-8 py-3 rounded-lg font-semibold text-primary-600 border border-primary-600 bg-white hover:bg-primary-50 shadow-sm text-lg transition">Learn More</a>
                                    @endauth
                                </div>
                </div>
                <div class="w-full lg:w-1/2 flex justify-center lg:justify-end mb-12 lg:mb-0 z-10">
                    <div class="relative">
                        <img class="rounded-2xl shadow-2xl w-full max-w-md object-cover border-4 border-white" src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Library">
                        <div class="absolute -bottom-6 -right-6 bg-primary-600 text-white px-6 py-3 rounded-xl shadow-lg text-lg font-semibold flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Smart. Simple. Powerful.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="bg-gray-50 border-t border-gray-100">
            <div class="max-w-7xl mx-auto py-20 px-4 sm:px-6 lg:py-28 lg:px-8">
                <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
                    <div>
                        <h2 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl mb-6">
                            About LibraSense
                        </h2>
                        <p class="mb-8 max-w-2xl text-xl text-gray-600">
                            LibraSense is a modern library management system designed to make library operations more efficient and user-friendly. Our platform combines powerful features with an intuitive interface to provide the best experience for both librarians and library users.
                        </p>
                        <ul class="space-y-4 mb-10">
                            <li class="flex items-start gap-3">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-primary-100 text-primary-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </span>
                                <span class="text-lg text-gray-800 font-medium">Effortless Book Management</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-primary-100 text-primary-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </span>
                                <span class="text-lg text-gray-800 font-medium">User-Centric Experience</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-primary-100 text-primary-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </span>
                                <span class="text-lg text-gray-800 font-medium">Streamlined Borrowing & Returns</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-primary-100 text-primary-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </span>
                                <span class="text-lg text-gray-800 font-medium">Powerful Analytics & Insights</span>
                            </li>
                        </ul>
                        <div class="bg-white border-l-4 border-primary-600 p-6 rounded-xl shadow mb-8">
                            <p class="text-lg text-gray-700 italic mb-2">“LibraSense has transformed the way we manage our library. The interface is beautiful, and our users love it!”</p>
                            <div class="flex items-center gap-3">
                                <span class="inline-block h-10 w-10 rounded-full bg-primary-200 flex items-center justify-center text-primary-700 font-bold">V</span>
                                <span class="text-gray-800 font-semibold">Vhans Chester F. Cabaltera</span>
                                <span class="text-gray-500 text-sm">Head Librarian</span>
                            </div>
                        </div>
                        <a href="{{ route('register') }}" class="inline-block px-8 py-3 border border-primary-600 text-primary-600 font-semibold rounded-lg bg-white hover:bg-primary-50 transition">Start Using LibraSense</a>
                    </div>
                    <div class="mt-12 lg:mt-0">
                        <img class="rounded-2xl shadow-2xl w-full object-cover border-4 border-white" src="https://plus.unsplash.com/premium_photo-1677567996070-68fa4181775a?q=80&w=2072&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="About LibraSense">
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100">
            <div class="max-w-7xl mx-auto py-10 px-4 overflow-hidden sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6">
                    <div class="flex items-center gap-3 justify-center md:justify-start">
                        <img src="{{ asset('images/logo.png') }}" alt="LibraSense Logo" class="h-8 w-auto" />
                        <span class="text-lg font-bold tracking-wide text-gray-900">LibraSense</span>
                    </div>
                    <nav class="flex flex-wrap justify-center gap-6" aria-label="Footer">
                        <a href="#about" class="text-base text-gray-500 hover:text-primary-600 transition">About</a>
                        <a href="#" class="text-base text-gray-500 hover:text-primary-600 transition">Privacy</a>
                        <a href="#" class="text-base text-gray-500 hover:text-primary-600 transition">Terms</a>
                    </nav>
                    <div class="flex justify-center gap-4">
                        <a href="#" class="text-gray-400 hover:text-primary-600 transition"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.56v14.91A4.56 4.56 0 0 1 19.44 24H4.56A4.56 4.56 0 0 1 0 19.47V4.56A4.56 4.56 0 0 1 4.56 0h14.91A4.56 4.56 0 0 1 24 4.56zM8.09 19.47V9.5H5.08v9.97zm-1.5-11.3a1.74 1.74 0 1 1 0-3.48 1.74 1.74 0 0 1 0 3.48zm15.41 11.3v-5.4c0-2.89-1.54-4.24-3.6-4.24a3.09 3.09 0 0 0-2.77 1.52h-.04V9.5h-3v9.97h3v-5.56c0-1.47.28-2.89 2.1-2.89s1.82 1.62 1.82 2.98v5.47z"/></svg></a>
                        <a href="#" class="text-gray-400 hover:text-primary-600 transition"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.954 4.569c-.885.389-1.83.654-2.825.775 1.014-.611 1.794-1.574 2.163-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-2.72 0-4.924 2.204-4.924 4.924 0 .386.045.763.127 1.124C7.691 8.095 4.066 6.13 1.64 3.161c-.423.722-.666 1.561-.666 2.475 0 1.708.87 3.216 2.188 4.099-.807-.026-1.566-.247-2.228-.616v.062c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.317 0-.626-.03-.928-.086.627 1.956 2.444 3.377 4.6 3.417-1.68 1.318-3.809 2.105-6.102 2.105-.396 0-.787-.023-1.175-.069 2.179 1.397 4.768 2.213 7.557 2.213 9.054 0 14.009-7.496 14.009-13.986 0-.21 0-.423-.016-.634.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                        <a href="#" class="text-gray-400 hover:text-primary-600 transition"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.334 3.608 1.308.974.974 1.246 2.242 1.308 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.334 2.633-1.308 3.608-.974.974-2.242 1.246-3.608 1.308-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.334-3.608-1.308-.974-.974-1.246-2.242-1.308-3.608C2.175 15.647 2.163 15.267 2.163 12s.012-3.584.07-4.85c.062-1.366.334-2.633 1.308-3.608.974-.974 2.242-1.246 3.608-1.308C8.416 2.175 8.796 2.163 12 2.163zm0-2.163C8.741 0 8.332.013 7.052.072 5.771.131 4.659.425 3.678 1.406 2.697 2.387 2.403 3.499 2.344 4.78.013 8.332 0 8.741 0 12c0 3.259.013 3.668.072 4.948.059 1.281.353 2.393 1.334 3.374.981.981 2.093 1.275 3.374 1.334C8.332 23.987 8.741 24 12 24c3.259 0 3.668-.013 4.948-.072 1.281-.059 2.393-.353 3.374-1.334.981-.981 1.275-2.093 1.334-3.374.059-1.28.072-1.689.072-4.948 0-3.259-.013-3.668-.072-4.948-.059-1.281-.353-2.393-1.334-3.374-.981-.981-2.093-1.275-3.374-1.334C15.668.013 15.259 0 12 0z"/></svg></a>
                    </div>
                    </div>
                <hr class="my-8 border-gray-200">
                <p class="text-center text-base text-gray-400">&copy; {{ date('Y') }} LibraSense. All rights reserved.</p>
            </div>
        </footer>

        <script>
            // Mobile menu toggle
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.querySelector('button[aria-controls="mobile-menu"]');
                const mobileMenu = document.getElementById('mobile-menu');
                if(mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    const expanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !expanded);
                    mobileMenu.classList.toggle('hidden');
                });
                }
            });
        </script>
    </body>
</html>
