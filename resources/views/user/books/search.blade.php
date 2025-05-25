@php
    use Illuminate\Support\Str;
@endphp
@section('title', 'Librasense - Book Search')
<x-app-layout>
    @section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-success-modal />
            
            <!-- Header with Search -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-2">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h2 class="text-2xl font-bold text-gray-900">Library Collection</h2>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <span>Total Books:</span>
                        <span class="font-semibold text-primary-600">{{ count($books) }}</span>
                    </div>
                </div>

                <!-- Search and Filter -->
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
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500"
                                >
                            </div>
                        </div>
                        <select 
                            x-model="category"
                            class="px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500"
                        >
                            <option value="">All Categories</option>
                            @foreach($books->pluck('category')->unique()->sort() as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                        <select 
                            x-model="availability"
                            class="px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500"
                        >
                            <option value="">All Availability</option>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Books Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($books as $book)
                    <div 
                        data-book
                        data-title="{{ $book->title }}"
                        data-author="{{ $book->author }}"
                        data-category="{{ $book->category }}"
                        data-status="{{ $book->status }}"
                        class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow"
                        x-data="{ showModal: false }"
                    >
                        <!-- Book Cover -->
                        <div class="aspect-[2/3] bg-primary-100 flex items-center justify-center overflow-hidden">
                            @if($book->cover_image)
                                @if(Str::startsWith($book->cover_image, ['http://', 'https://']))
                                    <img src="{{ $book->cover_image }}" alt="{{ $book->title }} cover" class="object-cover h-full w-full">
                                @else
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }} cover" class="object-cover h-full w-full">
                                @endif
                            @else
                                <span class="text-6xl font-bold text-primary-600">{{ substr($book->title, 0, 1) }}</span>
                            @endif
                        </div>
                        
                        <!-- Book Info -->
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1 truncate">{{ $book->title }}</h3>
                            <p class="text-sm text-gray-500 mb-2">by {{ $book->author }}</p>
                            
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $book->category }}
                                </span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $book->quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $book->quantity > 0 ? 'Available' : 'Unavailable' }}
                                </span>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <button @click="showModal = true" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Details
                                </button>
                                
                                @if($book->quantity > 0)
                                    <form method="POST" action="{{ route('user.loans.store') }}" class="flex-1" x-data="{ 
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
                                            class="w-full inline-flex items-center justify-center px-3 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
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
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 px-4" 
                                            aria-labelledby="modal-title" 
                                            role="dialog" 
                                            aria-modal="true"
                                        >
                                            <div class="bg-white rounded-lg max-w-lg w-full p-6 relative mx-4 sm:mx-0">
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
                                    </form>
                                @else
                                    <button disabled class="w-full inline-flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-400 text-sm font-medium rounded-lg cursor-not-allowed">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Unavailable
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Book Details Modal -->
                        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 px-4" @click.self="showModal = false">
                            <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6 relative mx-4 sm:mx-0">
                                <button @click="showModal = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="flex-shrink-0 aspect-[2/3] w-16 bg-primary-100 rounded-lg flex items-center justify-center overflow-hidden">
                                        @if($book->cover_image)
                                            @if(Str::startsWith($book->cover_image, ['http://', 'https://']))
                                                <img src="{{ $book->cover_image }}" alt="{{ $book->title }} cover" class="object-cover h-full w-full rounded-lg">
                                            @else
                                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }} cover" class="object-cover h-full w-full rounded-lg">
                                            @endif
                                        @else
                                            <span class="text-primary-600 font-bold text-3xl">{{ substr($book->title, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-xl font-semibold text-gray-900">{{ $book->title }}</div>
                                        <div class="text-sm text-gray-500">by {{ $book->author }}</div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $book->category }}</span>
                                </div>
                                <div class="mb-4 text-sm text-gray-700">
                                    <div class="flex items-center gap-2 mb-2">
                                        <strong>Quantity:</strong> {{ $book->quantity }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <strong>Status:</strong>
                                        <span class="{{ $book->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $book->quantity > 0 ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-gray-700">
                                    <strong>Description:</strong>
                                    <div class="mt-1 text-sm">{{ $book->description }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900">No books found</h3>
                                <p class="text-sm text-gray-500">Try adjusting your search or filters.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @endsection

    @if(session('error'))
        <x-error-modal :message="session('error')" />
    @endif

    <style>
    [x-cloak] { display: none !important; }
    </style>
</x-app-layout> 