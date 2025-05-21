<x-app-layout>
    @section('content')
    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="bg-yellow-50 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Share Your Feedback</h2>
                        <p class="text-gray-600 mt-1">Help us improve our library services</p>
                    </div>
                </div>

                @if (session('success'))
                    <div 
                        x-data="{ show: true }" 
                        x-show="show" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        x-init="setTimeout(() => show = false, 2000)"
                        class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg"
                    >
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-green-700 font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('user.feedback.store') }}" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="category" :value="__('Category')" class="font-semibold text-gray-700" />
                            <select id="category" name="category" class="mt-2 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-xl shadow-sm px-4 py-3 bg-white" required>
                                <option value="">Select a category</option>
                                <option value="library_services">Library Services</option>
                                <option value="book_collection">Book Collection</option>
                                <option value="facilities">Facilities</option>
                                <option value="staff">Staff</option>
                                <option value="website">Website/App</option>
                                <option value="suggestions">Suggestions</option>
                                <option value="other">Other</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category')" />
                        </div>
                        <div>
                            <x-input-label for="subject" :value="__('Subject')" class="font-semibold text-gray-700" />
                            <x-text-input id="subject" name="subject" type="text" class="mt-2 block w-full rounded-xl px-4 py-3" required autofocus placeholder="Brief summary of your feedback" />
                            <x-input-error class="mt-2" :messages="$errors->get('subject')" />
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-xl">
                        <x-input-label for="rating" :value="__('How would you rate your experience?')" class="font-semibold text-gray-700" />
                        <div class="flex items-center gap-2 mt-3" x-data="{ rating: 0 }">
                            @foreach(range(1, 5) as $star)
                                <label class="cursor-pointer group">
                                    <input type="radio" name="rating" value="{{ $star }}" class="hidden peer" required
                                        x-on:click="rating = {{ $star }}"
                                        x-on:change="rating = {{ $star }}">
                                    <svg class="w-10 h-10 transition-colors duration-150" 
                                        :class="{
                                            'text-yellow-400': rating >= {{ $star }},
                                            'text-gray-300': rating < {{ $star }},
                                            'group-hover:text-yellow-300': rating < {{ $star }}
                                        }"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z" />
                                    </svg>
                                </label>
                            @endforeach
                        </div>
                        <div class="flex justify-between text-sm text-gray-500 mt-2">
                            <span>Poor</span>
                            <span>Excellent</span>
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('rating')" />
                    </div>

                    <div>
                        <x-input-label for="message" :value="__('Your Feedback')" class="font-semibold text-gray-700" />
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="4" 
                            class="mt-2 block w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-xl shadow-sm px-4 py-3" 
                            required
                            placeholder="Please share your detailed feedback with us..."
                        ></textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('message')" />
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_anonymous" value="1" class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Submit as anonymous</span>
                        </label>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white text-lg font-semibold rounded-xl shadow hover:bg-primary-700 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout> 