@php use Illuminate\Support\Str; @endphp
@section('title', 'Librasense - Feedback Analytics')
<x-app-layout>
    <title>Librasense - Feedback Analytics</title>
    @section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Feedback Analytics</h1>
                <p class="mt-2 text-gray-600">Insights and trends from user feedback submissions.</p>
            </div>
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Feedback</p>
                            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $overallStats['total_feedback'] }}</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Average Rating</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($overallStats['average_rating'], 1) }}/5</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">This Month</p>
                            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $overallStats['this_month_count'] }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Anomalies</p>
                            <p class="text-3xl font-bold text-red-600 mt-2">{{ $overallStats['anomaly_count'] }}</p>
                        </div>
                        <div class="p-3 bg-red-50 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Rating Distribution Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Rating Distribution</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="ratingChart"></canvas>
                    </div>
                </div>

                <!-- Sentiment Analysis Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Sentiment Analysis</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="sentimentChart"></canvas>
                    </div>
                </div>

                <!-- Category Distribution Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Feedback Categories</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <!-- Recent Trends Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Trends</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="trendsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- 2x2 Grid for Four Aligned Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card 1: Latest Feedback -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col min-h-[400px] hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <div class="p-2 bg-yellow-50 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                            Latest Feedback
                        </h3>
                        <span class="text-sm text-gray-500">Last 7 days</span>
                    </div>
                    <div class="space-y-3 overflow-y-auto max-h-[320px] pr-2">
                        @foreach($latestFeedback as $feedback)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-yellow-600">{{ $feedback->user && $feedback->user->name ? substr($feedback->user->name, 0, 1) : '?' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-800">{{ $feedback->user->name ?? 'Anonymous' }}</span>
                                        <div class="text-sm text-gray-600">{{ Str::limit($feedback->subject, 40) }}</div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $feedback->rating >= 4 ? 'bg-green-100 text-green-800' : ($feedback->rating <= 2 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17.75l-6.172 3.245 1.179-6.873-5-4.873 6.9-1.002L12 2.5l3.093 6.747 6.9 1.002-5 4.873 1.179 6.873z" />
                                        </svg>
                                        {{ $feedback->rating }}/5
                                    </span>
                                    <span class="text-xs text-gray-500 mt-1">{{ $feedback->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Card 2: Actionable Suggestions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col min-h-[400px] hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <div class="p-2 bg-blue-50 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            Actionable Suggestions
                        </h3>
                        <span class="text-sm text-gray-500">Based on feedback</span>
                    </div>
                    <div class="overflow-y-auto max-h-[320px] pr-2">
                        <ul class="space-y-3">
                            @foreach($suggestions as $suggestion)
                                @if($suggestion['text'] !== 'Emerging issue' && $suggestion['text'] !== 'Most Requested Books')
                                    <li class="flex flex-col bg-blue-50 rounded-lg p-4 hover:bg-blue-100 transition-colors duration-200">
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-blue-900">{{ $suggestion['text'] }}</span>
                                            @if(!is_null($suggestion['count']))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    {{ $suggestion['count'] }} feedback{{ $suggestion['count'] > 1 ? 's' : '' }}
                                                </span>
                                            @endif
                                        </div>
                                        @if(!empty($suggestion['phrase']))
                                            <div class="mt-2 flex items-center text-sm text-blue-700">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Most common phrase: "{{ $suggestion['phrase'] }}"
                                            </div>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Card 3: Most Requested Books -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col min-h-[400px] hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <div class="p-2 bg-green-50 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            Most Requested Books
                        </h3>
                        <span class="text-sm text-gray-500">Popular requests</span>
                    </div>
                    <div class="overflow-y-auto max-h-[320px] pr-2">
                        @foreach($suggestions as $suggestion)
                            @if($suggestion['text'] === 'Most Requested Books' && !empty($suggestion['books']))
                                <ul class="space-y-3">
                                    @foreach($suggestion['books'] as $bookTitle => $count)
                                        <li class="flex items-center justify-between bg-green-50 rounded-lg p-4 hover:bg-green-100 transition-colors duration-200">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                                <span class="font-medium text-green-900">{{ $bookTitle }}</span>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                {{ $count }} request{{ $count > 1 ? 's' : '' }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Card 4: Emerging Issues -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col min-h-[400px] hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <div class="p-2 bg-orange-50 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            Emerging Issues
                        </h3>
                        <span class="text-sm text-gray-500">Requires attention</span>
                    </div>
                    <div class="overflow-y-auto max-h-[320px] pr-2">
                        <ul class="space-y-3">
                            @foreach($suggestions as $suggestion)
                                @if($suggestion['text'] === 'Emerging issue')
                                    <li class="flex flex-col bg-orange-50 rounded-lg p-4 hover:bg-orange-100 transition-colors duration-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                <span class="font-medium text-orange-900">Issue Detected</span>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                                {{ $suggestion['count'] }} feedback{{ $suggestion['count'] > 1 ? 's' : '' }}
                                            </span>
                                        </div>
                                        <div class="mt-2 text-sm text-orange-700">
                                            <span class="font-medium">Common phrase:</span> "{{ $suggestion['phrase'] }}"
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Common chart options for consistency
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

        // Rating Distribution Chart
        new Chart(document.getElementById('ratingChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($ratingDistribution->pluck('rating')) !!},
                datasets: [{
                    label: 'Number of Ratings',
                    data: {!! json_encode($ratingDistribution->pluck('count')) !!},
                    backgroundColor: [
                        '#EF4444', '#EF4444', '#F59E0B', '#10B981', '#10B981'
                    ],
                    borderRadius: 4
                }]
            },
            options: commonOptions
        });

        // Sentiment Analysis Chart
        new Chart(document.getElementById('sentimentChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($sentimentStats->pluck('sentiment')) !!},
                datasets: [{
                    data: {!! json_encode($sentimentStats->pluck('count')) !!},
                    backgroundColor: [
                        '#10B981', '#F59E0B', '#EF4444'
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

        // Category Distribution Chart
        new Chart(document.getElementById('categoryChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryStats->pluck('category')) !!},
                datasets: [{
                    data: {!! json_encode($categoryStats->pluck('count')) !!},
                    backgroundColor: [
                        '#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#6366F1', '#F472B6', '#34D399', '#F87171', '#FBBF24'
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

        // Recent Trends Chart
        new Chart(document.getElementById('trendsChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($recentTrends->pluck('date')) !!},
                datasets: [{
                    label: 'Number of Feedback',
                    data: {!! json_encode($recentTrends->pluck('count')) !!},
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Average Rating',
                    data: {!! json_encode($recentTrends->pluck('avg_rating')) !!},
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        max: 5,
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout> 