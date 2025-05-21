<x-app-layout>
    @section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Welcome back, Admin!</h1>
                <p class="mt-2 text-gray-600">Here's what's happening in your library today.</p>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Books</p>
                            <p class="text-3xl font-bold text-primary-600 mt-2">{{ $stats['total_books'] }}</p>
                            <p class="text-sm text-gray-500 mt-1">Available: {{ $stats['available_books'] }}</p>
                        </div>
                        <div class="p-3 bg-primary-50 rounded-lg">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active Loans</p>
                            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['total_loans'] }}</p>
                            <p class="text-sm text-red-500 mt-1">Overdue: {{ $stats['overdue_loans'] }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">User Engagement</p>
                            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['total_users'] }}</p>
                            <p class="text-sm text-gray-500 mt-1">Feedback: {{ $stats['total_feedback'] }}</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Loans -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Recent Loans
                            </h3>
                            <a href="{{ route('admin.loans.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View all</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($recent_loans as $loan)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                                <span class="text-primary-600 font-semibold">{{ $loan->user ? substr($loan->user->name, 0, 1) : '?' }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $loan->book->title }}</p>
                                            <p class="text-sm text-gray-500">Borrowed by {{ $loan->user ? $loan->user->name : 'Unknown User' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">{{ $loan->created_at->diffForHumans() }}</p>
                                        <p class="text-xs text-gray-500">Due: {{ $loan->due_date->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No recent loans</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Feedback -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                Recent Feedback
                            </h3>
                            <a href="{{ route('admin.feedback.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View all</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($recent_feedback as $feedback)
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                                    <span class="text-yellow-600 font-semibold">{{ $feedback->user ? substr($feedback->user->name, 0, 1) : '?' }}</span>
                                                </div>
                                            </div>
                                            <p class="font-medium text-gray-900">{{ $feedback->user ? $feedback->user->name : 'Anonymous' }}</p>
                                        </div>
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $feedback->message }}</p>
                                    <p class="text-xs text-gray-500 mt-2">{{ $feedback->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No recent feedback</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>         
        </div>
    </div>
    @endsection
</x-app-layout> 