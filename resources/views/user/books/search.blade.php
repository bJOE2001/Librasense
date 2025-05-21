<x-app-layout>
    @section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex items-center gap-2 mb-6">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h2 class="text-2xl font-bold text-gray-900">Search Books</h2>
            </div>

            <!-- Search and Filter Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
                <div x-data="{ 
                    query: '',
                    category: '',
                    availability: '',
                    filteredBooks() {
                        let books = document.querySelectorAll('[data-book]');
                        books.forEach(book => {
                            let title = book.getAttribute('data-title').toLowerCase();
                            let author = book.getAttribute('data-author').toLowerCase();
                            let bookCategory = book.getAttribute('data-category').toLowerCase();
                            let status = book.getAttribute('data-status').toLowerCase();
                            
                            let matchesQuery = this.query === '' || 
                                title.includes(this.query.toLowerCase()) || 
                                author.includes(this.query.toLowerCase());
                            let matchesCategory = this.category === '' || 
                                bookCategory === this.category.toLowerCase();
                            let matchesAvailability = this.availability === '' || 
                                status === this.availability.toLowerCase();
                            
                            book.style.display = (matchesQuery && matchesCategory && matchesAvailability) ? '' : 'none';
                        });
                    }
                }" x-init="$watch('query', () => filteredBooks()); $watch('category', () => filteredBooks()); $watch('availability', () => filteredBooks());">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <input 
                                    x-model="query"
                                    type="text" 
                                    placeholder="Search by title or author..." 
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500"
                                >
                            </div>
                        </div>
                        <select 
                            x-model="category"
                            class="px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500"
                        >
                            <option value="">All Categories</option>
                            <option value="Fiction">Fiction</option>
                            <option value="Non-Fiction">Non-Fiction</option>
                            <option value="Science">Science</option>
                            <option value="Technology">Technology</option>
                            <option value="History">History</option>
                        </select>
                        <select 
                            x-model="availability"
                            class="px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500"
                        >
                            <option value="">All Availability</option>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Books Table Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="overflow-x-auto" style="max-height: 600px;">
                    <table class="min-w-full text-sm">
                        <thead class="sticky top-0 bg-gray-50 z-10">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($books as $book)
                                <tr 
                                    data-book
                                    data-title="{{ $book->title }}"
                                    data-author="{{ $book->author }}"
                                    data-category="{{ $book->category }}"
                                    data-status="{{ $book->status }}"
                                    class="hover:bg-gray-50 transition-colors"
                                    x-data="{ showModal: false }"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                                <span class="text-primary-600 font-semibold">{{ substr($book->title, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $book->title }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $book->author }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $book->category }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $book->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $book->quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $book->quantity > 0 ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button @click="showModal = true" class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition">
                                            View
                                        </button>
                                        <!-- Book Details Modal -->
                                        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40" @click.self="showModal = false">
                                            <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6 relative">
                                                <button @click="showModal = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                                <div class="flex items-center gap-4 mb-4">
                                                    <div class="flex-shrink-0 h-12 w-12 bg-primary-100 rounded-lg flex items-center justify-center">
                                                        <span class="text-primary-600 font-bold text-2xl">{{ substr($book->title, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <div class="text-xl font-semibold text-gray-900">{{ $book->title }}</div>
                                                        <div class="text-sm text-gray-500">by {{ $book->author }}</div>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $book->category }}</span>
                                                </div>
                                                <div class="mb-2 text-sm text-gray-700">
                                                    <strong>Quantity:</strong> {{ $book->quantity }}<br>
                                                    <strong>Status:</strong> <span class="{{ $book->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $book->quantity > 0 ? 'Available' : 'Unavailable' }}</span>
                                                </div>
                                                <div class="mb-2 text-gray-700">
                                                    <strong>Description:</strong>
                                                    <div class="mt-1 text-sm">{{ $book->description }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($book->quantity > 0)
                                            <form method="POST" action="{{ route('user.loans.store') }}" class="inline" x-data="{ 
                                                showDatePicker: false,
                                                loanDate: '{{ now()->format('Y-m-d') }}',
                                                dueDate: '{{ now()->addDays(14)->format('Y-m-d') }}',
                                                minDueDate: '{{ now()->addDay()->format('Y-m-d') }}'
                                            }">
                                                @csrf
                                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                                <input type="hidden" name="loan_date" x-model="loanDate">
                                                <input type="hidden" name="due_date" x-model="dueDate">
                                                <button 
                                                    type="button" 
                                                    @click="showDatePicker = true"
                                                    class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                                >
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    Reserve
                                                </button>

                                                <!-- Date Picker Modal -->
                                                <div 
                                                    x-show="showDatePicker" 
                                                    x-cloak
                                                    class="fixed inset-0 z-50 overflow-y-auto" 
                                                    aria-labelledby="modal-title" 
                                                    role="dialog" 
                                                    aria-modal="true"
                                                    x-transition:enter="ease-out duration-300"
                                                    x-transition:enter-start="opacity-0"
                                                    x-transition:enter-end="opacity-100"
                                                    x-transition:leave="ease-in duration-200"
                                                    x-transition:leave-start="opacity-100"
                                                    x-transition:leave-end="opacity-0"
                                                >
                                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                        <div 
                                                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                                                            aria-hidden="true"
                                                            @click="showDatePicker = false"
                                                        ></div>

                                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                                        <div 
                                                            class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                                                            @click.stop
                                                        >
                                                            <div>
                                                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-1">
                                                                    Reserve: <span class="font-semibold">{{ $book->title }}</span>
                                                                </h3>
                                                                <p class="text-sm text-gray-500 mb-3">
                                                                    Please select your desired loan and due dates.
                                                                </p>
                                                                <div class="space-y-4">
                                                                    <div>
                                                                        <label for="loan_date" class="block text-sm font-medium text-gray-700">Loan Date</label>
                                                                        <input 
                                                                            type="date" 
                                                                            id="loan_date" 
                                                                            x-model="loanDate"
                                                                            :min="'{{ now()->format('Y-m-d') }}'"
                                                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                                        >
                                                                    </div>
                                                                    <div>
                                                                        <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                                                                        <input 
                                                                            type="date" 
                                                                            id="due_date" 
                                                                            x-model="dueDate"
                                                                            :min="minDueDate"
                                                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                                                <button 
                                                                    type="submit"
                                                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:col-start-2 sm:text-sm"
                                                                >
                                                                    Confirm Reservation
                                                                </button>
                                                                <button 
                                                                    type="button"
                                                                    @click="showDatePicker = false"
                                                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:col-start-1 sm:text-sm"
                                                                >
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        @else
                                            <button disabled class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-400 text-sm font-medium rounded-lg cursor-not-allowed">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Unavailable
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900">No books found</h3>
                                            <p class="text-sm text-gray-500">Try adjusting your search or filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endsection

    <!-- Reservation Error Modal -->
    @if(session('error') && session('reserved_by'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 5000)"
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Book Unavailable
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                The book "{{ session('book_title') }}" is currently fully reserved by:
                            </p>
                            <p class="mt-2 text-sm font-medium text-gray-900">
                                {{ session('reserved_by') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button @click="show = false" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
    [x-cloak] { display: none !important; }
    </style>
</x-app-layout> 