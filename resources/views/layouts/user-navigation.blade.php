<!-- Sidebar Navigation -->
<div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg hidden lg:block">
    <!-- Logo & Brand -->
    <div class="flex items-center justify-center h-20 border-b border-gray-200">
        <img src="{{ asset('images/logo.png') }}" alt="LibraSense Logo" class="h-14 w-auto mr-3">
        <span class="text-lg font-bold tracking-wide" style="color: #2a2b2a;">LibraSense</span>
    </div>

    <!-- Navigation Links -->
    <nav class="mt-6 px-4 space-y-2">
        <x-side-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')" icon="dashboard">
            {{ __('Dashboard') }}
        </x-side-link>

        <!-- Library Section -->
        <div class="pt-4">
            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Library
            </h3>
            <div class="mt-2 space-y-1">
                <x-side-link :href="route('user.book-search')" :active="request()->routeIs('user.book-search')" icon="book">
                    {{ __('Search Books') }}
                </x-side-link>
                @if(Route::has('user.loans'))
                <x-side-link :href="route('user.loans')" :active="request()->routeIs('user.loans')" icon="loans">
                    {{ __('My Loans') }}
                </x-side-link>
                @endif
                <x-side-link :href="route('user.feedback')" :active="request()->routeIs('user.feedback')" icon="chat">
                    {{ __('Feedback') }}
                </x-side-link>
            </div>
        </div>
    </nav>

    <!-- User Profile Section -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
        <div class="flex items-center mb-3">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                    <span class="text-primary-600 font-semibold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <a href="{{ route('user.settings') }}" class="w-full flex items-center justify-center px-4 py-2 mb-2 text-sm font-medium text-gray-700 border border-gray-200 bg-white hover:bg-gray-50 hover:border-primary-400 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 transition">
            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-1.14 1.603-1.14 1.902 0a1.724 1.724 0 002.573 1.01c.943-.545 2.042.454 1.497 1.397a1.724 1.724 0 001.01 2.573c1.14.3 1.14 1.603 0 1.902a1.724 1.724 0 00-1.01 2.573c.545.943-.454 2.042-1.397 1.497a1.724 1.724 0 00-2.573 1.01c-.3 1.14-1.603 1.14-1.902 0a1.724 1.724 0 00-2.573-1.01c-.943.545-2.042-.454-1.497-1.397a1.724 1.724 0 00-1.01-2.573c-1.14-.3-1.14-1.603 0-1.902a1.724 1.724 0 001.01-2.573c-.545-.943.454-2.042 1.397-1.497.943.545 2.042-.454 1.497-1.397z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ __('Settings') }}
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 mt-2 text-sm font-medium text-red-600 border border-red-200 bg-white hover:bg-red-50 hover:border-red-400 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
                </svg>
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</div>

<!-- Mobile Navigation -->
<div x-data="{ open: false }" class="lg:hidden">
    <!-- Mobile menu button -->
    <div class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200">
        <div class="flex items-center justify-between h-16 px-4">
            <div class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="LibraSense Logo" class="h-8 w-auto mr-2">
                <span class="text-lg font-bold tracking-wide" style="color: #2a2b2a;">LibraSense</span>
            </div>
            <button @click="open = !open" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500">
                <span class="sr-only">Open main menu</span>
                <svg class="h-6 w-6" x-show="!open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg class="h-6 w-6" x-show="open" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile menu panel -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-x-full"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 -translate-x-full"
         class="fixed inset-0 z-40 flex"
         style="display: none;">
        
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="open = false"></div>

        <!-- Mobile menu content -->
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
            <div class="flex-1 h-0 pt-16 pb-4 overflow-y-auto">
                <nav class="mt-5 px-2 space-y-1">
                    <x-responsive-side-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')" icon="dashboard">
                        {{ __('Dashboard') }}
                    </x-responsive-side-link>

                    <!-- Library -->
                    <div class="pt-4">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Library
                        </h3>
                        <div class="mt-2 space-y-1">
                            <x-responsive-side-link :href="route('user.book-search')" :active="request()->routeIs('user.book-search')" icon="book">
                                {{ __('Search Books') }}
                            </x-responsive-side-link>
                            @if(Route::has('user.loans'))
                            <x-responsive-side-link :href="route('user.loans')" :active="request()->routeIs('user.loans')" icon="loans">
                                {{ __('My Loans') }}
                            </x-responsive-side-link>
                            @endif
                            <x-responsive-side-link :href="route('user.feedback')" :active="request()->routeIs('user.feedback')" icon="chat">
                                {{ __('Feedback') }}
                            </x-responsive-side-link>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Mobile user profile -->
            <div class="flex-shrink-0 flex flex-col border-t border-gray-200 p-4">
                <div class="flex items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <span class="text-primary-600 font-semibold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <a href="{{ route('user.settings') }}" class="w-full flex items-center justify-center px-4 py-2 mb-2 text-sm font-medium text-gray-700 border border-gray-200 bg-white hover:bg-gray-50 hover:border-primary-400 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 transition">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-1.14 1.603-1.14 1.902 0a1.724 1.724 0 002.573 1.01c.943-.545 2.042.454 1.497 1.397a1.724 1.724 0 001.01 2.573c1.14.3 1.14 1.603 0 1.902a1.724 1.724 0 00-1.01 2.573c.545.943-.454 2.042-1.397 1.497a1.724 1.724 0 00-2.573 1.01c-.3 1.14-1.603 1.14-1.902 0a1.724 1.724 0 00-2.573-1.01c-.943.545-2.042-.454-1.497-1.397a1.724 1.724 0 00-1.01-2.573c-1.14-.3-1.14-1.603 0-1.902a1.724 1.724 0 001.01-2.573c-.545-.943.454-2.042 1.397-1.497.943.545 2.042-.454 1.497-1.397z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ __('Settings') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 mt-2 text-sm font-medium text-red-600 border border-red-200 bg-white hover:bg-red-50 hover:border-red-400 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
                        </svg>
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="lg:ml-64">
    <!-- Page Content -->
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            @yield('content')
        </div>
    </div>
</div> 