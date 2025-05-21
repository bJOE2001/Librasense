<x-app-layout>
    @section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Library Analytics</h1>
                <p class="mt-2 text-gray-600">Comprehensive insights about your library's performance and usage.</p>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Visitors -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Visitors</p>
                            <p class="text-3xl font-bold text-primary-600 mt-2">{{ $visitorStats['total_visitors'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">This Month: {{ $visitorStats['monthly_visitors'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-primary-50 rounded-lg">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Loans -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active Loans</p>
                            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $loanStats['active_loans'] ?? 0 }}</p>
                            <p class="text-sm text-red-500 mt-1">Overdue: {{ $loanStats['overdue_loans'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Book Circulation -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Book Circulation</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ $bookStats['total_circulation'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">This Month: {{ $bookStats['monthly_circulation'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- User Engagement -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">User Engagement</p>
                            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $userStats['active_users'] ?? 0 }}</p>
                            <p class="text-sm text-gray-500 mt-1">New This Month: {{ $userStats['new_users'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Visitor Trends -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Visitor Trends</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="visitorTrendsChart"></canvas>
                    </div>
                </div>

                <!-- Book Circulation Trends -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Book Circulation Trends</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="circulationTrendsChart"></canvas>
                    </div>
                </div>

                <!-- Popular Categories -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Popular Categories</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="categoriesChart"></canvas>
                    </div>
                </div>

                <!-- User Activity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">User Activity</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="userActivityChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Visits -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Recent Visits
                            </h3>
                            <a href="{{ route('admin.library-visits.log') }}" class="text-sm text-primary-600 hover:text-primary-700">View all</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($recentVisits ?? [] as $visit)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                                <span class="text-primary-600 font-semibold">{{ $visit->user ? substr($visit->user->name, 0, 1) : '?' }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $visit->user ? $visit->user->name : 'Unknown User' }}</p>
                                            <p class="text-sm text-gray-500">{{ $visit->entry_time->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">{{ $visit->duration ?? 'Active' }}</p>
                                        <p class="text-xs text-gray-500">{{ $visit->exit_time ? 'Exited' : 'Currently in library' }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No recent visits</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Popular Books -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Popular Books
                            </h3>
                            <a href="{{ route('admin.books.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View all</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($popularBooks ?? [] as $book)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                                <span class="text-yellow-600 font-semibold">{{ substr($book->title, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $book->title }}</p>
                                            <p class="text-sm text-gray-500">By {{ $book->author }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">{{ $book->total_loans ?? 0 }} loans</p>
                                        <p class="text-xs text-gray-500">{{ $book->category }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No popular books data</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Common chart options
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        };

        // Visitor Trends Chart
        const visitorTrendsCtx = document.getElementById('visitorTrendsChart').getContext('2d');
        new Chart(visitorTrendsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($visitorTrends['labels'] ?? []) !!},
                datasets: [{
                    label: 'Daily Visitors',
                    data: {!! json_encode($visitorTrends['data'] ?? []) !!},
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: commonOptions
        });

        // Circulation Trends Chart
        const circulationTrendsCtx = document.getElementById('circulationTrendsChart').getContext('2d');
        new Chart(circulationTrendsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($circulationTrends['labels'] ?? []) !!},
                datasets: [{
                    label: 'Books Loaned',
                    data: {!! json_encode($circulationTrends['data'] ?? []) !!},
                    backgroundColor: '#10B981',
                    borderRadius: 4
                }]
            },
            options: commonOptions
        });

        // Categories Chart
        const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
        new Chart(categoriesCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryStats['labels'] ?? []) !!},
                datasets: [{
                    data: {!! json_encode($categoryStats['data'] ?? []) !!},
                    backgroundColor: [
                        '#4F46E5',
                        '#10B981',
                        '#F59E0B',
                        '#EF4444',
                        '#8B5CF6'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // User Activity Chart
        const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
        new Chart(userActivityCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($userActivity['labels'] ?? []) !!},
                datasets: [{
                    label: 'Active Users',
                    data: {!! json_encode($userActivity['data'] ?? []) !!},
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: commonOptions
        });
    </script>
    @endpush
    @endsection
</x-app-layout> 