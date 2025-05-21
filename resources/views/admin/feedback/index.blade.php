@php use Illuminate\Support\Str; @endphp
<x-app-layout>
    @section('content')
    <div class="w-full mx-auto py-8">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="flex items-center gap-4 mb-8">
                <h2 class="text-3xl font-bold flex items-center gap-3">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8a5 5 0 00-10 0v4a5 5 0 0010 0V8zm-5 8v.01" /></svg>
                    Feedback List
                </h2>
                <a href="{{ route('admin.feedback.analytics') }}" class="ml-auto px-6 py-3 text-lg font-medium text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8a5 5 0 00-10 0v4a5 5 0 0010 0V8zm-5 8v.01" />
                    </svg>
                    Analytics
                </a>
            </div>

            <!-- Feedback Table Card -->
            <div class="bg-white rounded-2xl shadow-lg w-full p-6" x-data="{
                search: '',
                category: '',
                rating: '',
                sentiment: '',
                is_anomaly: '',
                get filteredRows() {
                    let rows = $refs.feedbackRows.querySelectorAll('[data-user][data-category][data-rating][data-sentiment][data-anomaly]');
                    rows.forEach(row => {
                        let user = row.getAttribute('data-user').toLowerCase();
                        let category = row.getAttribute('data-category');
                        let rating = row.getAttribute('data-rating');
                        let sentiment = row.getAttribute('data-sentiment');
                        let anomaly = row.getAttribute('data-anomaly');
                        let matchesSearch = this.search === '' || user.includes(this.search.toLowerCase());
                        let matchesCategory = this.category === '' || category === this.category;
                        let matchesRating = this.rating === '' || rating === this.rating;
                        let matchesSentiment = this.sentiment === '' || sentiment === this.sentiment;
                        let matchesAnomaly = this.is_anomaly === '' || anomaly === this.is_anomaly;
                        row.style.display = (matchesSearch && matchesCategory && matchesRating && matchesSentiment && matchesAnomaly) ? '' : 'none';
                    });
                }
            }" x-effect="filteredRows()">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <h2 class="text-xl font-semibold flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8a5 5 0 00-10 0v4a5 5 0 0010 0V8zm-5 8v.01" /></svg>
                        All Feedback
                    </h2>
                    <div class="flex flex-wrap gap-2 w-full md:w-auto">
                        <input x-model="search" type="text" placeholder="Search by user name..." class="flex-1 px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 shadow-sm" />
                        <select x-model="category" class="px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 shadow-sm">
                            <option value="">All Categories</option>
                            <option value="library_services">Library Services</option>
                            <option value="book_collection">Book Collection</option>
                            <option value="facilities">Facilities</option>
                            <option value="staff">Staff</option>
                            <option value="website">Website/App</option>
                            <option value="suggestions">Suggestions</option>
                            <option value="other">Other</option>
                        </select>
                        <select x-model="rating" class="px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 shadow-sm">
                            <option value="">All Ratings</option>
                            @for($i=1; $i<=5; $i++)
                                <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                        <select x-model="sentiment" class="px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 shadow-sm">
                            <option value="">All Sentiments</option>
                            <option value="positive">Positive</option>
                            <option value="negative">Negative</option>
                            <option value="neutral">Neutral</option>
                        </select>
                        <select x-model="is_anomaly" class="px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 shadow-sm">
                            <option value="">All Types</option>
                            <option value="1">Anomalies</option>
                            <option value="0">Normal</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sentiment</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anomaly</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody x-ref="feedbackRows" class="bg-white divide-y divide-gray-200">
                            @foreach ($feedback as $item)
                                <tr class="hover:bg-primary-50/40 transition-colors" 
                                    data-user="{{ strtolower($item->user->name ?? '') }}" 
                                    data-category="{{ $item->category }}" 
                                    data-rating="{{ $item->rating }}"
                                    data-sentiment="{{ $item->sentiment }}"
                                    data-anomaly="{{ $item->is_anomaly ? '1' : '0' }}">
                                    <td class="px-3 py-2">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-600">{{ $item->user && $item->user->name ? substr($item->user->name, 0, 1) : '?' }}</span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900">{{ $item->user->name ?? 'Anonymous' }}</div>
                                                <div class="text-gray-500 text-xs">{{ $item->user->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 capitalize">{{ str_replace('_', ' ', $item->category) }}</td>
                                    <td class="px-3 py-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $item->rating >= 4 ? 'bg-green-100 text-green-800' : ($item->rating <= 2 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17.75l-6.172 3.245 1.179-6.873-5-4.873 6.9-1.002L12 2.5l3.093 6.747 6.9 1.002-5 4.873 1.179 6.873z" />
                                            </svg>
                                            {{ $item->rating }}/5
                                        </span>
                                    </td>
                                    <td class="px-3 py-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $item->sentiment === 'positive' ? 'bg-green-100 text-green-800' : ($item->sentiment === 'negative' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($item->sentiment) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2">
                                        @if($item->is_anomaly)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                Anomaly
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Normal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 font-medium text-gray-900">{{ $item->subject }}</td>
                                    <td class="px-3 py-2 text-gray-700" title="{{ $item->message }}">{{ Str::limit($item->message, 40) }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>
<!-- Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script> 