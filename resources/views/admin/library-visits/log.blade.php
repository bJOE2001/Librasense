<x-app-layout>
    @section('content')
    <div class="max-w-5xl mx-auto py-8">
        <div class="flex items-center gap-4 mb-8">
            <h1 class="text-3xl font-bold flex items-center gap-3">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                User Presence Log
            </h1>
            <span class="inline-flex items-center px-4 py-2 rounded-xl bg-green-100 text-green-800 text-lg font-semibold shadow-sm">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                {{ $currentInside }} currently inside
            </span>
            <button id="open-qr-scanner" class="ml-auto px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 font-semibold shadow flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                Scan QR
            </button>
        </div>
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
                <div id="qr-success-check" style="display:none; margin: 20px 0; text-align:center;">
                    <svg class="h-12 w-12 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="white"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M7 13l3 3 7-7" />
                    </svg>
                    <div class="text-green-600 mt-2 font-semibold">Success!</div>
                </div>
                <button onclick="closeQrModal()" class="mt-2 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
            </div>
        </div>

        {{-- Modal for Success/Error Messages --}}
        @if(session('success') || session('error'))
            <div 
                x-data="{ 
                    open: true,
                    init() {
                        setTimeout(() => {
                            this.open = false;
                        }, 1500);
                    }
                }" 
                x-show="open" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30"
                style="backdrop-filter: blur(2px);"
            >
                <div 
                    @click.away="open = false"
                    class="bg-white rounded-xl shadow-lg p-6 max-w-md w-full border border-gray-200 flex items-start gap-3 transform transition-all"
                >
                    <div>
                        @if(session('success'))
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-lg">
                            @if(session('success'))
                                @if(str_contains(session('success'), 'entry'))
                                    Entry Recorded Successfully
                                @elseif(str_contains(session('success'), 'exit'))
                                    Exit Recorded Successfully
                                @else
                                    Success
                                @endif
                            @else
                                Error
                            @endif
                        </div>
                        <div class="mt-1 text-gray-700">
                            @if(session('success'))
                                @if(str_contains(session('success'), 'entry'))
                                    <p class="font-medium">{{ session('success') }}</p>
                                    <p class="text-sm text-gray-500 mt-1">The user's entry time has been recorded in the system.</p>
                                @elseif(str_contains(session('success'), 'exit'))
                                    <p class="font-medium">{{ session('success') }}</p>
                                    <p class="text-sm text-gray-500 mt-1">The user's exit time has been recorded in the system.</p>
                                @else
                                    {{ session('success') }}
                                @endif
                            @else
                                <p class="font-medium">{{ session('error') }}</p>
                                <p class="text-sm text-gray-500 mt-1">Please try again or contact support if the issue persists.</p>
                            @endif
                        </div>
                    </div>
                    <button @click="open = false" class="ml-4 text-gray-400 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- User Info & Mark In/Out --}}
        @if($user)
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 sticky top-20 z-10 border border-primary-100 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="bg-primary-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <div class="font-semibold text-xl text-gray-900">{{ $user->name }}</div>
                        <div class="text-sm text-gray-500">ID: {{ $user->student_id ?? 'N/A' }} <span class="mx-2">|</span> <span class="inline-block px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs font-semibold">Student</span></div>
                        @if($openVisit)
                            <div class="mt-2 text-sm text-gray-600">
                                <span class="inline-flex items-center gap-1"
                                      x-data="{ duration: '{{ $openVisit->current_duration }}' }"
                                      x-init="
                                        function updateDuration() {
                                            const entryTime = new Date('{{ $openVisit->entry_time?->toIso8601String() }}');
                                            const now = new Date();
                                            let diffMinutes = Math.floor((now - entryTime) / (1000 * 60));
                                            diffMinutes = Math.max(diffMinutes, 0);
                                            const hours = Math.floor(diffMinutes / 60);
                                            const minutes = diffMinutes % 60;
                                            duration = hours > 0 ? `${hours}h ${minutes}m` : `${minutes}m`;
                                        }
                                        updateDuration();
                                        setInterval(updateDuration, 60000);
                                      ">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Duration: <span x-text="duration"></span>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2">
                    @if(!$openVisit)
                        <form method="POST" action="{{ route('admin.library-visits.markEntry') }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="hidden" name="name" value="{{ $user->name }}">
                            <button class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 font-semibold shadow">Mark Entry</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.library-visits.markExit') }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 font-semibold shadow">Mark Exit</button>
                        </form>
                    @endif
                </div>
            </div>
        @elseif($query)
            <div class="bg-yellow-100 text-yellow-800 rounded-xl p-4 mb-8 shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m2 0a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                <span>No user found for "{{ $query }}"</span>
            </div>
        @endif

        {{-- All Users Table with Search and Filter --}}
        <div x-data="{
            search: '',
            filterStatus: 'all',
            filterType: 'all',
            get filteredUsers() {
                let users = $refs.userRows.querySelectorAll('[data-name]');
                users.forEach(row => {
                    let name = row.getAttribute('data-name').toLowerCase();
                    let status = row.getAttribute('data-status');
                    let type = row.getAttribute('data-type');
                    let matchesSearch = this.search === '' || name.includes(this.search.toLowerCase());
                    let matchesStatus = this.filterStatus === 'all' || status === this.filterStatus;
                    let matchesType = this.filterType === 'all' || type === this.filterType;
                    row.style.display = (matchesSearch && matchesStatus && matchesType) ? '' : 'none';
                });
            }
        }" x-init="$watch('search', () => filteredUsers()); $watch('filterStatus', () => filteredUsers()); $watch('filterType', () => filteredUsers());" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <h2 class="text-xl font-semibold flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    All Users
                </h2>
                <div class="flex flex-1 justify-end gap-2 items-center">
                    <div class="relative w-64">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4-4m0 0A7 7 0 105 5a7 7 0 0012 12z" /></svg>
                        </span>
                        <input x-model="search" type="text" placeholder="Search users..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 shadow-sm" autocomplete="off">
                    </div>
                    <select x-model="filterStatus" class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="all">All Status</option>
                        <option value="inside">Inside</option>
                        <option value="outside">Outside</option>
                    </select>
                    <select x-model="filterType" class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="all">All Types</option>
                        <option value="student">Student</option>
                        <option value="non-student">Non-Student</option>
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto" style="max-height: 500px; overflow-y: auto;">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody x-ref="userRows" class="bg-white divide-y divide-gray-200">
                        @forelse($users as $u)
                            <tr class="border-b last:border-0 hover:bg-primary-50/40 transition-colors {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}" data-name="{{ strtolower($u->name) }}" data-status="{{ $u->is_inside ? 'inside' : 'outside' }}" data-type="{{ $u->role && $u->role->name === 'non-student' ? 'non-student' : 'student' }}">
                                <td class="px-3 py-2 font-medium text-gray-900">{{ $u->name }}</td>
                                <td class="px-3 py-2">
                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold 
                                        @if($u->role && $u->role->name === 'student') bg-blue-100 text-blue-700
                                        @else bg-green-100 text-green-700 @endif">
                                        {{ ($u->role && $u->role->name === 'non-student') ? 'Non-Student' : 'Student' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2">
                                    @if($u->is_inside)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" /></svg>
                                            Inside
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" /></svg>
                                            Outside
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-gray-600">
                                    @if($u->is_inside && $u->current_visit)
                                        <span class="inline-flex items-center gap-1"
                                              x-data="{ duration: '{{ $u->current_visit->current_duration }}' }"
                                              x-init="
                                                function updateDuration() {
                                                    const entryTime = new Date('{{ $u->current_visit->entry_time?->toIso8601String() }}');
                                                    const now = new Date();
                                                    let diffMinutes = Math.floor((now - entryTime) / (1000 * 60));
                                                    diffMinutes = Math.max(diffMinutes, 0);
                                                    const hours = Math.floor(diffMinutes / 60);
                                                    const minutes = diffMinutes % 60;
                                                    duration = hours > 0 ? `${hours}h ${minutes}m` : `${minutes}m`;
                                                }
                                                updateDuration();
                                                setInterval(updateDuration, 60000);
                                              ">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <span x-text="duration"></span>
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center space-x-2">
                                        @if(!$u->is_inside)
                                            <form method="POST" action="{{ route('admin.library-visits.markEntry') }}" class="inline">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $u->id }}">
                                                <input type="hidden" name="name" value="{{ $u->name }}">
                                                <input type="hidden" name="type" value="{{ $u->type }}">
                                                <button class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium transition-colors">In</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.library-visits.markExit') }}" class="inline">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $u->id }}">
                                                <button class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium transition-colors">Out</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>No users found.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        let qrModal = document.getElementById('qr-modal');
        let qrScanner = null;
        let scanning = false;
        let spinner = document.getElementById('qr-loading-spinner');
        let successCheck = document.getElementById('qr-success-check');

        document.getElementById('open-qr-scanner').onclick = function() {
            qrModal.style.display = 'flex';
            scanning = true;
            spinner.style.display = 'none';
            successCheck.style.display = 'none';
            qrScanner = new Html5Qrcode("qr-reader");
            qrScanner.start(
                { facingMode: "environment" },
                { fps: 25, qrbox: 300 },
                qrCodeMessage => {
                    if (!scanning) return;
                    scanning = false;
                    spinner.style.display = 'block';
                    fetch('{{ route('admin.library-visits.scan') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ qr_code: qrCodeMessage })
                    })
                    .then(res => res.json())
                    .then(data => {
                        spinner.style.display = 'none';
                        successCheck.style.display = 'block';
                        setTimeout(() => {
                            qrScanner.stop();
                            qrModal.style.display = 'none';
                            successCheck.style.display = 'none';
                            location.reload();
                        }, 1200);
                    })
                    .catch(() => {
                        spinner.style.display = 'none';
                        scanning = true;
                        // Optionally show an error icon/message here
                    });
                }
            );
        };

        function closeQrModal() {
            qrModal.style.display = 'none';
            scanning = false;
            spinner.style.display = 'none';
            successCheck.style.display = 'none';
            if (qrScanner) {
                qrScanner.stop();
            }
        }
    </script>
    @endpush
    @endsection
</x-app-layout> 