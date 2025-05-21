<x-app-layout>
    @section('content')
    <div class="max-w-5xl mx-auto py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ showSuccess: false, showError: false, showApprovedBooks: {{ session('approved_books') ? 'true' : 'false' }}, showReturnedBooks: {{ session('returned_books') ? 'true' : 'false' }} }" x-init="
                @if(session('success'))
                    showSuccess = true;
                    setTimeout(() => showSuccess = false, 2500);
                @endif
                @if($errors->any())
                    showError = true;
                    setTimeout(() => showError = false, 3500);
                @endif
            ">
                <!-- Feedback Modal (only if not QR scan result) -->
                @if((session('success') || $errors->any()) && !session('approved_books') && !session('returned_books'))
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
                @endif
            </div>
            <!-- Page Header -->
            <div class="flex items-center gap-4 mb-8">
                <h2 class="text-3xl font-bold flex items-center gap-3">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Loans
                </h2>
                <div class="flex gap-2 ml-auto">
                    <button
                        id="open-qr-scanner"
                        type="button"
                        class="px-6 py-3 text-lg font-medium text-white bg-green-600 rounded-xl hover:bg-green-700 shadow-sm flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7V5a2 2 0 012-2h2m8 0h2a2 2 0 012 2v2m0 8v2a2 2 0 01-2 2h-2m-8 0H5a2 2 0 01-2-2v-2" />
                        </svg>
                        Scan QR Code
                    </button>
                    <button
                        type="button"
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'add-loan' }))"
                        class="px-6 py-3 text-lg font-medium text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-sm flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Loan
                    </button>
                </div>
            </div>

            <!-- Loans Table Card -->
            <div class="bg-white rounded-2xl shadow-lg w-full p-6" x-data="{
                search: '',
                status: '',
                get filteredLoans() {
                    return Array.from($refs.loanRows.children).filter(row => {
                        const book = row.getAttribute('data-book') || '';
                        const user = row.getAttribute('data-user') || '';
                        const status = (row.getAttribute('data-status') || '').toLowerCase().trim();
                        const filterStatus = this.status.toLowerCase().trim();
                        const matchesSearch = this.search === '' || book.includes(this.search.toLowerCase()) || user.includes(this.search.toLowerCase());
                        const matchesStatus = filterStatus === '' || status === filterStatus;
                        return matchesSearch && matchesStatus;
                    });
                },
                updateRows() {
                    Array.from($refs.loanRows.children).forEach(row => row.style.display = 'none');
                    this.filteredLoans.forEach(row => row.style.display = '');
                }
            }" x-init="$watch('search', () => updateRows()); $watch('status', () => updateRows());">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <h2 class="text-xl font-semibold flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        All Loans
                    </h2>
                    <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                        <div class="relative w-full md:w-64">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4-4m0 0A7 7 0 105 5a7 7 0 0012 12z" /></svg>
                            </span>
                            <input x-model="search" type="text" placeholder="Search by book or user..." class="flex-1 pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 shadow-sm w-full" />
                        </div>
                        <select x-model="status" class="border border-gray-300 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="overdue">Overdue</option>
                            <option value="returned">Returned</option>
                            <option value="reserved">Reserved</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Date</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody x-ref="loanRows" class="bg-white divide-y divide-gray-200">
                            @forelse ($loans as $loan)
                                <tr class="hover:bg-primary-50/40 transition-colors {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}"
                                    data-book="{{ strtolower($loan->book->title ?? '') }}"
                                    data-user="{{ strtolower($loan->user->name ?? '') }}"
                                    data-status="{{ $loan->return_date ? 'returned' : ($loan->status === 'reserved' ? 'reserved' : ($loan->is_overdue ? 'overdue' : 'active')) }}">
                                    <td class="px-3 py-2 font-medium text-gray-900">
                                        {{ $loan->book->title ?? 'N/A' }}
                                        <div class="text-xs text-gray-500">ISBN: {{ $loan->book->isbn ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-3 py-2">
                                        <span class="inline-block bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold">{{ $loan->user->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-3 py-2 text-gray-600">{{ $loan->loan_date->format('M d, Y') }}</td>
                                    <td class="px-3 py-2 text-gray-600">{{ $loan->due_date->format('M d, Y') }}</td>
                                    <td class="px-3 py-2">
                                        @if($loan->return_date)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-200 text-gray-600">
                                                Returned
                                            </span>
                                        @elseif($loan->is_overdue)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Overdue
                                            </span>
                                        @elseif($loan->status === 'declined')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-200 text-red-800">
                                                Declined
                                            </span>
                                        @elseif($loan->status === 'reserved')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Reserved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="flex items-center space-x-2">
                                            @if($loan->status === 'reserved')
                                                <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition mr-1" title="Approve Reservation">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.loans.decline', $loan) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition" title="Decline Reservation">
                                                        Decline
                                                    </button>
                                                </form>
                                            @else
                                                @if($loan->status !== 'active' && $loan->status !== 'declined' && !$loan->return_date)
                                                    <button @click="$dispatch('open-modal', 'edit-loan-{{ $loan->id }}')" class="text-gray-400 hover:text-primary-600 transition-colors" title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                @endif
                                                @if($loan->status !== 'active' && $loan->status !== 'declined' && $loan->return_date)
                                                    <button @click="$dispatch('open-modal', 'delete-loan-{{ $loan->id }}')" class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition" title="Delete">
                                                        Delete
                                                    </button>
                                                @elseif($loan->status !== 'active' && $loan->status !== 'declined')
                                                    <button @click="$dispatch('open-modal', 'delete-loan-{{ $loan->id }}')" class="text-red-400 hover:text-red-600 transition-colors" title="Delete">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                @endif
                                                @if(!$loan->return_date && $loan->status !== 'declined')
                                                    <button @click="$dispatch('open-modal', 'return-loan-{{ $loan->id }}')" class="px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition" title="Return">
                                                        Return
                                                    </button>
                                                @endif
                                            @endif
                                            @if($loan->status === 'declined')
                                                <button @click="$dispatch('open-modal', 'delete-loan-{{ $loan->id }}')" class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition" title="Delete">
                                                    Delete
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12 text-gray-400">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>No loans found.</span>
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

    <!-- Add Loan Modal -->
    <x-modal name="add-loan" :show="false">
        <form method="POST" action="{{ route('admin.loans.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900">Add New Loan</h2>
            <div class="mt-6 space-y-6">
                <!-- User -->
                <div>
                    <x-input-label for="user_id" value="User" />
                    <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required>
                        <option value="">Select a user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                </div>
                <!-- Book -->
                <div>
                    <x-input-label for="book_id" value="Book" />
                    <select id="book_id" name="book_id" class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required>
                        <option value="">Select a book</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }} (Available: {{ $book->quantity }})</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('book_id')" class="mt-2" />
                </div>
                <!-- Loan Date -->
                <div>
                    <x-input-label for="loan_date" value="Loan Date" />
                    <x-text-input id="loan_date" name="loan_date" type="date" class="mt-1 block w-full" value="{{ date('Y-m-d') }}" required />
                    <x-input-error :messages="$errors->get('loan_date')" class="mt-2" />
                </div>
                <!-- Due Date -->
                <div>
                    <x-input-label for="due_date" value="Due Date" />
                    <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" value="{{ date('Y-m-d', strtotime('+14 days')) }}" required />
                    <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'add-loan')">Cancel</x-secondary-button>
                <x-primary-button class="ml-3">Add Loan</x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Edit & Delete Modals for Each Loan -->
    @foreach ($loans as $loan)
        <!-- Edit Loan Modal -->
        <x-modal name="edit-loan-{{ $loan->id }}" :show="false">
            <form method="POST" action="{{ route('admin.loans.update', $loan) }}" class="p-6">
                @csrf
                @method('PUT')
                <h2 class="text-lg font-medium text-gray-900">Edit Loan</h2>
                <div class="mt-6 space-y-6">
                    <!-- User -->
                    <div>
                        <x-input-label for="user_id_{{ $loan->id }}" value="User" />
                        <select id="user_id_{{ $loan->id }}" name="user_id" class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required>
                            <option value="">Select a user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $loan->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                    </div>
                    <!-- Book -->
                    <div>
                        <x-input-label for="book_id_{{ $loan->id }}" value="Book" />
                        <select id="book_id_{{ $loan->id }}" name="book_id" class="mt-1 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" required>
                            <option value="">Select a book</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}" {{ $loan->book_id == $book->id ? 'selected' : '' }}>{{ $book->title }} (Available: {{ $book->quantity }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('book_id')" class="mt-2" />
                    </div>
                    <!-- Loan Date -->
                    <div>
                        <x-input-label for="loan_date_{{ $loan->id }}" value="Loan Date" />
                        <x-text-input id="loan_date_{{ $loan->id }}" name="loan_date" type="date" class="mt-1 block w-full" value="{{ $loan->loan_date->format('Y-m-d') }}" required />
                        <x-input-error :messages="$errors->get('loan_date')" class="mt-2" />
                    </div>
                    <!-- Due Date -->
                    <div>
                        <x-input-label for="due_date_{{ $loan->id }}" value="Due Date" />
                        <x-text-input id="due_date_{{ $loan->id }}" name="due_date" type="date" class="mt-1 block w-full" value="{{ $loan->due_date->format('Y-m-d') }}" required />
                        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'edit-loan-{{ $loan->id }}')">Cancel</x-secondary-button>
                    <x-primary-button class="ml-3">Update Loan</x-primary-button>
                </div>
            </form>
        </x-modal>

        <!-- Delete Loan Modal -->
        <x-modal name="delete-loan-{{ $loan->id }}" :show="false">
            <form method="POST" action="{{ route('admin.loans.destroy', $loan) }}" class="p-6">
                @csrf
                @method('DELETE')
                <h2 class="text-lg font-medium text-gray-900">Delete Loan</h2>
                <p class="mt-1 text-sm text-gray-600">Are you sure you want to delete this loan for <strong>{{ $loan->book->title ?? 'N/A' }}</strong> borrowed by <strong>{{ $loan->user->name ?? 'N/A' }}</strong>? This action cannot be undone.</p>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'delete-loan-{{ $loan->id }}')">Cancel</x-secondary-button>
                    <x-danger-button class="ml-3">Delete Loan</x-danger-button>
                </div>
            </form>
        </x-modal>

        <!-- Return Loan Modal -->
        <x-modal name="return-loan-{{ $loan->id }}" :show="false">
            <form method="POST" action="{{ route('admin.loans.return', $loan) }}" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-gray-900">Return Loan</h2>
                <p class="mt-1 text-sm text-gray-600">Mark the book <strong>{{ $loan->book->title ?? 'N/A' }}</strong> borrowed by <strong>{{ $loan->user->name ?? 'N/A' }}</strong> as returned?</p>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'return-loan-{{ $loan->id }}')">Cancel</x-secondary-button>
                    <x-primary-button class="ml-3">Return Book</x-primary-button>
                </div>
            </form>
        </x-modal>
    @endforeach

    <!-- QR Code Scanner Modal (Unified) -->
    <div id="qr-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
        <div style="background:#fff; padding:20px; border-radius:8px; display:flex; flex-direction:column; align-items:center;">
            <div id="qr-reader" style="width:300px"></div>
            <div id="qr-loading-spinner" style="display:none; margin: 20px 0;">
                <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                <div class="text-primary-600 mt-2 font-semibold">Processing...</div>
            </div>
            <form id="qr-scan-form" method="POST" action="{{ route('admin.loans.scanQr') }}" class="hidden">
                @csrf
                <input type="hidden" name="qr_code" id="qr_code_input">
            </form>
            <div id="qr-status" class="mt-4 text-center text-sm text-gray-600"></div>
            <button onclick="closeQrModal()" class="mt-2 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
        </div>
    </div>
    <!-- Confirmation Modal (Unified) -->
    @if((session('approved_books') || session('returned_books')) && session('scanned_user_name'))
    <div x-data="{ showScanResult: true }" x-init="setTimeout(() => showScanResult = false, 1500)" x-show="showScanResult" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30" style="display: flex;">
        <div class="bg-white rounded-lg shadow-lg p-8 min-w-[350px] flex flex-col items-center animate-fade-in">
            <svg class="h-12 w-12 text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <h3 class="text-xl font-bold mb-2 text-green-700">QR Scan Processed!</h3>
            <p class="mb-2 text-gray-700">User: <span class="font-semibold text-primary-700">{{ session('scanned_user_name') }}</span></p>
            @if(session('approved_books'))
                <p class="mb-2 text-gray-700">The following books have been <span class="font-semibold text-green-700">approved</span>:</p>
                <ul class="mb-4 text-gray-800 list-disc list-inside">
                    @foreach(session('approved_books') as $title)
                        <li>{{ $title }}</li>
                    @endforeach
                </ul>
            @endif
            @if(session('returned_books'))
                <p class="mb-2 text-gray-700">The following books have been <span class="font-semibold text-blue-700">returned</span>:</p>
                <ul class="mb-4 text-gray-800 list-disc list-inside">
                    @foreach(session('returned_books') as $title)
                        <li>{{ $title }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    @endif
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let qrScanner = null;
        function openQrModal() {
            document.getElementById('qr-modal').style.display = 'flex';
            setTimeout(startQrScanner, 200);
        }
        function closeQrModal() {
            document.getElementById('qr-modal').style.display = 'none';
            stopQrScanner();
        }
        function startQrScanner() {
            if (qrScanner) {
                qrScanner.clear();
            }
            qrScanner = new Html5Qrcode("qr-reader");
            qrScanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                qrCodeMessage => {
                    document.getElementById('qr_code_input').value = qrCodeMessage;
                    document.getElementById('qr-scan-form').submit();
                    document.getElementById('qr-loading-spinner').style.display = 'block';
                    stopQrScanner();
                },
                errorMessage => {
                    // Optionally show scan errors
                }
            );
        }
        function stopQrScanner() {
            if (qrScanner) {
                qrScanner.stop();
            }
        }
        document.getElementById('open-qr-scanner').addEventListener('click', openQrModal);
    </script>
    @endsection
</x-app-layout> 