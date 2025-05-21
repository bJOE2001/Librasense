@php use Illuminate\Support\Str; @endphp
<x-app-layout>
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
                    <canvas id="ratingChart" height="300"></canvas>
                </div>

                <!-- Sentiment Analysis Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Sentiment Analysis</h3>
                    <canvas id="sentimentChart" height="300"></canvas>
                </div>

                <!-- Category Distribution Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Feedback Categories</h3>
                    <canvas id="categoryChart" height="300"></canvas>
                </div>

                <!-- Recent Trends Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Trends</h3>
                    <canvas id="trendsChart" height="300"></canvas>
                </div>
            </div>

            <!-- Latest Feedback -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    Latest Feedback
                </h3>
                <div class="space-y-4">
                    @foreach($latestFeedback as $feedback)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-yellow-600">{{ $feedback->user && $feedback->user->name ? substr($feedback->user->name, 0, 1) : '?' }}</span>
                                </div>
                                <span class="font-medium text-gray-800">{{ $feedback->user->name ?? 'Anonymous' }}</span>
                            </div>
                            <div class="flex-1 mx-4">
                                <div class="font-medium text-gray-900">{{ $feedback->subject }}</div>
                                <div class="text-gray-600 text-sm">{{ Str::limit($feedback->message, 60) }}</div>
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
        </div>
    </div>
    @endsection

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Rating Distribution Chart
        new Chart(document.getElementById('ratingChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($ratingDistribution->pluck('rating')) !!},
                datasets: [{
                    label: 'Number of Ratings',
                    data: {!! json_encode($ratingDistribution->pluck('count')) !!},
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.2)',
                        'rgba(239, 68, 68, 0.2)',
                        'rgba(234, 179, 8, 0.2)',
                        'rgba(34, 197, 94, 0.2)',
                        'rgba(34, 197, 94, 0.2)'
                    ],
                    borderColor: [
                        'rgb(239, 68, 68)',
                        'rgb(239, 68, 68)',
                        'rgb(234, 179, 8)',
                        'rgb(34, 197, 94)',
                        'rgb(34, 197, 94)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Sentiment Analysis Chart
        new Chart(document.getElementById('sentimentChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($sentimentStats->pluck('sentiment')) !!},
                datasets: [{
                    data: {!! json_encode($sentimentStats->pluck('count')) !!},
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.2)',
                        'rgba(234, 179, 8, 0.2)',
                        'rgba(239, 68, 68, 0.2)'
                    ],
                    borderColor: [
                        'rgb(34, 197, 94)',
                        'rgb(234, 179, 8)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Category Distribution Chart
        new Chart(document.getElementById('categoryChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($categoryStats->pluck('category')) !!},
                datasets: [{
                    data: {!! json_encode($categoryStats->pluck('count')) !!},
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.2)',
                        'rgba(16, 185, 129, 0.2)',
                        'rgba(245, 158, 11, 0.2)',
                        'rgba(139, 92, 246, 0.2)',
                        'rgba(236, 72, 153, 0.2)',
                        'rgba(14, 165, 233, 0.2)',
                        'rgba(168, 85, 247, 0.2)'
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(139, 92, 246)',
                        'rgb(236, 72, 153)',
                        'rgb(14, 165, 233)',
                        'rgb(168, 85, 247)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Recent Trends Chart
        new Chart(document.getElementById('trendsChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($recentTrends->pluck('date')) !!},
                datasets: [{
                    label: 'Number of Feedback',
                    data: {!! json_encode($recentTrends->pluck('count')) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Average Rating',
                    data: {!! json_encode($recentTrends->pluck('avg_rating')) !!},
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
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
                            stepSize: 1
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