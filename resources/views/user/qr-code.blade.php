<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Your Library QR Code</h2>
                    <p class="text-gray-600 mb-8">Scan this QR code at the library entrance for quick access.</p>

                    <!-- QR Code Display -->
                    <div class="inline-block p-4 bg-white rounded-lg shadow-lg mb-8">
                        <div class="w-64 h-64 bg-gray-100 flex items-center justify-center">
                            <!-- Replace this with actual QR code generation -->
                            <div class="text-center">
                                <div class="text-4xl mb-2">📱</div>
                                <p class="text-sm text-gray-600">{{ $user->qr_code }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- User Information -->
                    <div class="mb-8">
                        <p class="text-gray-800 font-medium">{{ $user->name }}</p>
                        <p class="text-gray-600">{{ $user->email }}</p>
                    </div>

                    <!-- Download Button -->
                    <button class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download QR Code
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 