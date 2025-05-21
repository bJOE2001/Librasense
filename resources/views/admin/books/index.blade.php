<x-app-layout>
    @section('content')
    <div class="max-w-5xl mx-auto py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ showSuccess: false, showError: false }" x-init="
                @if(session('success'))
                    showSuccess = true;
                    setTimeout(() => showSuccess = false, 2500);
                @endif
                @if($errors->any())
                    showError = true;
                    setTimeout(() => showError = false, 3500);
                @endif
            ">
                <!-- Feedback Modal -->
                <div x-show="showSuccess || showError" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30" style="display: none;">
                    <div class="bg-white rounded-lg shadow-lg p-6 min-w-[300px] flex flex-col items-center animate-fade-in">
                        <template x-if="showSuccess">
                            <div class="flex flex-col items-center gap-3">
                                <svg x-show="!$el.classList.contains('done')" class="animate-spin h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                </svg>
                                <svg x-show="$el.classList.add('done')" class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="font-semibold text-green-700 text-center">{{ session('success') }}</span>
                            </div>
                        </template>
                        <template x-if="showError">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <ul class="list-disc pl-5 text-red-700 text-left">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <!-- Page Header -->
            <div class="flex items-center gap-4 mb-8">
                <h2 class="text-3xl font-bold flex items-center gap-3">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Books
                </h2>
                <button
                    type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'add-book' }))"
                    class="ml-auto px-6 py-3 text-lg font-medium text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-sm flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Book
                </button>
            </div>

            <!-- Books Table Card -->
            <div class="bg-white rounded-2xl shadow-lg w-full p-6" x-data="{
                search: '',
                category: '',
                availability: '',
                get filteredBooks() {
                    return Array.from($refs.bookRows.children).filter(row => {
                        const title = row.getAttribute('data-title') || '';
                        const author = row.getAttribute('data-author') || '';
                        const category = (row.getAttribute('data-category') || '').toLowerCase().trim();
                        const available = row.getAttribute('data-available');
                        const filterCategory = this.category.toLowerCase().trim();
                        const matchesSearch = this.search === '' || title.includes(this.search.toLowerCase()) || author.includes(this.search.toLowerCase());
                        const matchesCategory = filterCategory === '' || category === filterCategory;
                        const matchesAvailability = this.availability === '' || available === this.availability;
                        return matchesSearch && matchesCategory && matchesAvailability;
                    });
                },
                updateRows() {
                    Array.from($refs.bookRows.children).forEach(row => row.style.display = 'none');
                    this.filteredBooks.forEach(row => row.style.display = '');
                }
            }" x-init="$watch('search', () => updateRows()); $watch('category', () => updateRows()); $watch('availability', () => updateRows());">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <h2 class="text-xl font-semibold flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        All Books
                    </h2>
                    <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                        <div class="relative w-full md:w-64">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4-4m0 0A7 7 0 105 5a7 7 0 0012 12z" /></svg>
                            </span>
                            <input x-model="search" type="text" placeholder="Search by title, author, or ISBN..." class="flex-1 pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 shadow-sm w-full" />
                        </div>
                        <select x-model="category" class="border border-gray-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">All Categories</option>
                            <option value="fiction">Fiction</option>
                            <option value="non-fiction">Non-Fiction</option>
                            <option value="science">Science</option>
                            <option value="history">History</option>
                            <option value="biography">Biography</option>
                        </select>
                        <select x-model="availability" class="border border-gray-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">All Availability</option>
                            <option value="1">Available</option>
                            <option value="0">Unavailable</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ISBN</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Availability</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody x-ref="bookRows" class="bg-white divide-y divide-gray-200">
                            @forelse ($books as $book)
                                <tr class="hover:bg-primary-50/40 transition-colors {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}" data-title="{{ strtolower($book->title) }}" data-author="{{ strtolower($book->author) }}" data-category="{{ $book->category }}" data-available="{{ $book->is_available ? '1' : '0' }}">
                                    <td class="px-3 py-2 font-medium text-gray-900">{{ $book->title }}
                                        <div class="text-xs text-gray-500">Added {{ $book->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-3 py-2">
                                        <span class="inline-block bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold">{{ $book->author }}</span>
                                    </td>
                                    <td class="px-3 py-2 text-gray-600">{{ $book->isbn }}</td>
                                    <td class="px-3 py-2">
                                        <span class="inline-block bg-green-100 text-green-800 px-2 py-0.5 rounded-full font-semibold">{{ $book->category }}</span>
                                    </td>
                                    <td class="px-3 py-2 text-gray-600">{{ $book->quantity }}</td>
                                    <td class="px-3 py-2">
                                        @if($book->quantity > 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Available
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Unavailable
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="flex items-center space-x-2">
                                            <button @click="$dispatch('open-modal', 'edit-book-{{ $book->id }}')" class="text-gray-400 hover:text-primary-600 transition-colors" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button @click="$dispatch('open-modal', 'delete-book-{{ $book->id }}')" class="text-red-400 hover:text-red-600 transition-colors" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-12 text-gray-400">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                            <span>No books found.</span>
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

    <!-- Add Book Modal -->
    <x-modal name="add-book" :show="false">
        <form method="POST" action="{{ route('admin.books.store') }}" class="p-6" enctype="multipart/form-data" x-data="{ quantity: 0 }">
            @csrf
            <h2 class="text-lg font-medium text-gray-900">
                Add New Book
            </h2>

            <div class="mt-6 space-y-6">
                <!-- Title -->
                <div>
                    <x-input-label for="title" value="Title" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <!-- Author -->
                <div>
                    <x-input-label for="author" value="Author" />
                    <x-text-input id="author" name="author" type="text" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('author')" class="mt-2" />
                </div>

                <!-- ISBN -->
                <div>
                    <x-input-label for="isbn" value="ISBN" />
                    <x-text-input id="isbn" name="isbn" type="text" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('isbn')" class="mt-2" />
                </div>

                <!-- Category -->
                <div>
                    <x-input-label for="category" value="Category" />
                    <select id="category" name="category" class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required>
                        <option value="">Select a category</option>
                        <option value="fiction">Fiction</option>
                        <option value="non-fiction">Non-Fiction</option>
                        <option value="science">Science</option>
                        <option value="history">History</option>
                        <option value="biography">Biography</option>
                    </select>
                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                </div>

                <!-- Location -->
                <div>
                    <x-input-label for="location" value="Location" />
                    <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" required placeholder="e.g., Shelf A-1, Row 2" />
                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                </div>

                <!-- Quantity -->
                <div>
                    <x-input-label for="quantity" value="Quantity" />
                    <x-text-input id="quantity" name="quantity" type="number" min="0" class="mt-1 block w-full" required x-model.number="quantity" />
                    <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                    <div class="mt-2">
                        <span class="text-xs font-semibold" :class="quantity > 0 ? 'text-green-600' : 'text-gray-500'">
                            Availability: <span x-text="quantity > 0 ? 'Available' : 'Unavailable'"></span>
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm"></textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'add-book')">
                    Cancel
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    Add Book
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Book Modal -->
    @foreach ($books as $book)
        <x-modal name="edit-book-{{ $book->id }}" :show="false">
            <form method="POST" action="{{ route('admin.books.update', $book) }}" class="p-6" enctype="multipart/form-data" x-data="{ quantity: {{ old('quantity', $book->quantity) }} }">
                @csrf
                @method('PUT')
                <h2 class="text-lg font-medium text-gray-900">
                    Edit Book
                </h2>

                <div class="mt-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <x-input-label for="title_{{ $book->id }}" value="Title" />
                        <x-text-input id="title_{{ $book->id }}" name="title" type="text" class="mt-1 block w-full" :value="old('title', $book->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Author -->
                    <div>
                        <x-input-label for="author_{{ $book->id }}" value="Author" />
                        <x-text-input id="author_{{ $book->id }}" name="author" type="text" class="mt-1 block w-full" :value="old('author', $book->author)" required />
                        <x-input-error :messages="$errors->get('author')" class="mt-2" />
                    </div>

                    <!-- ISBN -->
                    <div>
                        <x-input-label for="isbn_{{ $book->id }}" value="ISBN" />
                        <x-text-input id="isbn_{{ $book->id }}" name="isbn" type="text" class="mt-1 block w-full" :value="old('isbn', $book->isbn)" required />
                        <x-input-error :messages="$errors->get('isbn')" class="mt-2" />
                    </div>

                    <!-- Category -->
                    <div>
                        <x-input-label for="category_{{ $book->id }}" value="Category" />
                        <select id="category_{{ $book->id }}" name="category" class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required>
                            <option value="">Select a category</option>
                            <option value="fiction" {{ $book->category === 'fiction' ? 'selected' : '' }}>Fiction</option>
                            <option value="non-fiction" {{ $book->category === 'non-fiction' ? 'selected' : '' }}>Non-Fiction</option>
                            <option value="science" {{ $book->category === 'science' ? 'selected' : '' }}>Science</option>
                            <option value="history" {{ $book->category === 'history' ? 'selected' : '' }}>History</option>
                            <option value="biography" {{ $book->category === 'biography' ? 'selected' : '' }}>Biography</option>
                        </select>
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </div>

                    <!-- Location -->
                    <div>
                        <x-input-label for="location_{{ $book->id }}" value="Location" />
                        <x-text-input id="location_{{ $book->id }}" name="location" type="text" class="mt-1 block w-full" :value="old('location', $book->location)" required placeholder="e.g., Shelf A-1, Row 2" />
                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                    </div>

                    <!-- Quantity -->
                    <div>
                        <x-input-label for="quantity_{{ $book->id }}" value="Quantity" />
                        <x-text-input id="quantity_{{ $book->id }}" name="quantity" type="number" min="0" class="mt-1 block w-full" :value="old('quantity', $book->quantity)" required x-model.number="quantity" />
                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        <div class="mt-2">
                            <span class="text-xs font-semibold" :class="quantity > 0 ? 'text-green-600' : 'text-gray-500'">
                                Availability: <span x-text="quantity > 0 ? 'Available' : 'Unavailable'"></span>
                            </span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label for="description_{{ $book->id }}" value="Description" />
                        <textarea id="description_{{ $book->id }}" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">{{ old('description', $book->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'edit-book-{{ $book->id }}')">
                        Cancel
                    </x-secondary-button>

                    <x-primary-button class="ml-3">
                        Update Book
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <!-- Delete Book Modal -->
        <x-modal name="delete-book-{{ $book->id }}" :show="false">
            <form method="POST" action="{{ route('admin.books.destroy', $book) }}" class="p-6">
                @csrf
                @method('DELETE')
                <h2 class="text-lg font-medium text-gray-900">
                    Delete Book
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Are you sure you want to delete "{{ $book->title }}"? This action cannot be undone.
                </p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'delete-book-{{ $book->id }}')">
                        Cancel
                    </x-secondary-button>

                    <x-danger-button class="ml-3">
                        Delete Book
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    @endforeach

    @if(request('search') || request('category') || request('availability'))
        <form method="GET" action="" class="mt-2">
            <button type="submit" class="text-sm text-primary-600 hover:underline">Clear Filters</button>
        </form>
    @endif
</x-app-layout> 