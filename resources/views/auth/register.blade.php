<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - LibraSense</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-image: url('https://www.transparenttextures.com/patterns/paper-fibers.png');
        }
        .fade-in {
            animation: fadeIn 0.8s cubic-bezier(.4,0,.2,1);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: none; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 to-white py-12 px-4 sm:px-6 lg:px-8 font-sans antialiased">
    <div class="max-w-7xl w-full space-y-8 fade-in">
        <div class="flex flex-col items-center">
            <img class="h-16 w-auto mb-4 animate-bounce" src="{{ asset('images/logo.png') }}" alt="LibraSense Logo">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center">Create your account</h2>
            <p class="mt-2 text-base text-gray-500 text-center">Join LibraSense and manage your library experience.</p>
        </div>
        <div class="bg-white py-8 px-6 shadow-2xl border border-gray-100 rounded-2xl relative">
            @if (session('success'))
                <div x-data="{ open: true }" x-show="open" x-transition class="mb-4 p-4 rounded-md bg-green-50 border border-green-200 flex items-start gap-3 animate-pulse">
                    <svg class="h-5 w-5 text-green-400 mt-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16z" clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1">
                        <span class="text-green-800 text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="open = false" class="ml-4 text-gray-400 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            @if ($errors->any())
                <div x-data="{ open: true }" x-show="open" x-transition class="mb-4 p-4 rounded-md bg-red-50 border border-red-200 flex items-start gap-3 animate-pulse">
                    <svg class="h-5 w-5 text-red-400 mt-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button @click="open = false" class="ml-4 text-gray-400 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            <form class="space-y-6" method="POST" action="{{ route('register') }}" x-data="registerForm()" @submit.prevent="submitForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-5">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-900">Full Name</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <input id="name" name="name" type="text" required x-model="name"
                                    :class="nameValid === true ? 'ring-2 ring-green-400' : nameValid === false ? 'ring-2 ring-red-400' : 'ring-1 ring-inset ring-gray-300'"
                                    @input="validateName"
                                    class="block w-full rounded-md border-0 py-2 pr-10 shadow-sm placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <template x-if="nameValid === true">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </template>
                                    <template x-if="nameValid === false">
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </template>
                                </span>
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-900">Email address</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <input id="email" name="email" type="email" required x-model="email"
                                    :class="emailValid === true ? 'ring-2 ring-green-400' : emailValid === false ? 'ring-2 ring-red-400' : 'ring-1 ring-inset ring-gray-300'"
                                    @input="validateEmail"
                                    class="block w-full rounded-md border-0 py-2 pr-10 shadow-sm placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <template x-if="emailValid === true">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </template>
                                    <template x-if="emailValid === false">
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </template>
                                </span>
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-900">Phone Number</label>
                            <div class="mt-2">
                                <input id="phone" name="phone" type="tel" x-model="phone"
                                    class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
                                    placeholder="Enter your phone number (e.g., +1234567890)">
                                <p class="mt-1 text-sm text-gray-500">
                                    Please include country code if applicable.
                                </p>
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-900">Address</label>
                            <div class="mt-2">
                                <textarea id="address" name="address" rows="3" x-model="address"
                                    class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
                                    placeholder="Enter your full address"></textarea>
                                @error('address')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-5">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-900">Password</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required x-model="password"
                                    @input="checkPasswordStrength"
                                    class="block w-full rounded-md border-0 py-2 pr-10 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                <button type="button" @click="showPassword = !showPassword" :aria-label="showPassword ? 'Hide password' : 'Show password'"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-primary-600 focus:outline-none">
                                    <template x-if="showPassword">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10 0-1.657.336-3.234.938-4.675M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </template>
                                    <template x-if="!showPassword">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </template>
                                </button>
                            </div>
                            <div class="mt-2">
                                <div class="w-full h-2 rounded bg-gray-200 overflow-hidden">
                                    <div :class="passwordStrengthClass" class="h-2 rounded transition-all duration-300" :style="'width:' + passwordStrengthPercent + '%'" ></div>
                                </div>
                                <p class="mt-1 text-sm" :class="passwordStrengthTextClass" x-text="passwordStrengthText"></p>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Password must be at least 8 characters long and include uppercase, lowercase, numbers, and symbols.
                            </p>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-900">Confirm Password</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <input :type="showPassword ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required x-model="passwordConfirmation"
                                    class="block w-full rounded-md border-0 py-2 pr-10 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <!-- User Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900">User Type</label>
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center">
                                    <input id="role_student" name="role" type="radio" value="student" checked
                                        class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600">
                                    <label for="role_student" class="ml-3 block text-sm leading-6 text-gray-700">Student</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="role_user" name="role" type="radio" value="non-student"
                                        class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600">
                                    <label for="role_user" class="ml-3 block text-sm leading-6 text-gray-700">Non-Student</label>
                                </div>
                            </div>
                        </div>

                        <!-- School -->
                        <div id="schoolField">
                            <label for="school" class="block text-sm font-medium text-gray-900">School</label>
                            <div class="mt-2">
                                <select id="school" name="school" x-model="school"
                                    class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    <option value="">Select your school</option>
                                    <option value="University of Mindanao – Tagum College (UMTC)">University of Mindanao – Tagum College (UMTC)</option>
                                    <option value="University of Southeastern Philippines – Tagum-Mabini Campus">University of Southeastern Philippines – Tagum-Mabini Campus</option>
                                    <option value="St. Mary's College of Tagum, Inc.">St. Mary's College of Tagum, Inc.</option>
                                    <option value="Arriesgado College Foundation, Inc. (ACFI)">Arriesgado College Foundation, Inc. (ACFI)</option>
                                    <option value="North Davao College – Tagum Foundation">North Davao College – Tagum Foundation</option>
                                    <option value="Liceo de Davao – Tagum City">Liceo de Davao – Tagum City</option>
                                    <option value="Tagum Doctors College, Inc.">Tagum Doctors College, Inc.</option>
                                    <option value="ACES Tagum College">ACES Tagum College</option>
                                    <option value="STI College – Tagum">STI College – Tagum</option>
                                    <option value="Queen of Apostles College Seminary">Queen of Apostles College Seminary</option>
                                    <option value="Tagum City College of Science and Technology Foundation, Inc.">Tagum City College of Science and Technology Foundation, Inc.</option>
                                    <option value="Tagum Longford College">Tagum Longford College</option>
                                    <option value="St. Thomas More School of Law and Business">St. Thomas More School of Law and Business</option>
                                    <option value="Philippine Nippon Technical College">Philippine Nippon Technical College</option>
                                    <option value="CARD-MRI Development Institute, Inc. (CMDI)">CARD-MRI Development Institute, Inc. (CMDI)</option>
                                    <option value="AMA Computer Learning Center – Tagum Campus (ACLC)">AMA Computer Learning Center – Tagum Campus (ACLC)</option>
                                    <option value="Computer Innovation Center (CIC)">Computer Innovation Center (CIC)</option>
                                    <option value="Philippine Institute of Technical Education (PITE)">Philippine Institute of Technical Education (PITE)</option>
                                    <option value="St. John Learning Center of Tagum City">St. John Learning Center of Tagum City</option>
                                    <option value="St. Michael Technical School">St. Michael Technical School</option>
                                    <option value="others">Others (Please specify)</option>
                                </select>
                                <div x-show="school === 'others'" class="mt-2">
                                    <input type="text" name="other_school" x-model="otherSchool"
                                        class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
                                        placeholder="Enter your school name">
                                </div>
                                @error('school')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" :disabled="loading"
                        class="flex w-full justify-center rounded-lg bg-primary-600 px-4 py-2.5 text-base font-semibold leading-6 text-white shadow-lg hover:bg-primary-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition disabled:opacity-60 disabled:cursor-not-allowed">
                        <template x-if="loading">
                            <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                        </template>
                        <span x-text="loading ? 'Creating account...' : 'Create account'"></span>
                    </button>
                </div>
            </form>
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-500 transition">
                        Sign in
                    </a>
                </p>
            </div>
            <div class="mt-8 flex flex-col items-center justify-center gap-2">
                <span class="text-gray-400 text-xs">&copy; {{ date('Y') }} LibraSense</span>
                <div class="flex gap-4 mt-2">
                    <a href="#" class="text-xs text-gray-400 hover:text-primary-600 transition">Terms of Service</a>
                    <a href="#" class="text-xs text-gray-400 hover:text-primary-600 transition">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function registerForm() {
            return {
                name: '',
                email: '',
                password: '',
                passwordConfirmation: '',
                showPassword: false,
                loading: false,
                nameValid: null,
                emailValid: null,
                school: '',
                otherSchool: '',
                phone: '',
                address: '',
                passwordStrength: 0,
                passwordStrengthText: '',
                passwordStrengthClass: 'bg-gray-300',
                passwordStrengthTextClass: 'text-gray-400',
                validateName() {
                    this.nameValid = this.name.length > 1;
                },
                validateEmail() {
                    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    this.emailValid = this.email.length === 0 ? null : re.test(this.email);
                },
                checkPasswordStrength() {
                    const val = this.password;
                    let score = 0;
                    if (val.length >= 8) score++;
                    if (/[A-Z]/.test(val)) score++;
                    if (/[a-z]/.test(val)) score++;
                    if (/[0-9]/.test(val)) score++;
                    if (/[^A-Za-z0-9]/.test(val)) score++;
                    this.passwordStrength = score;
                    if (score <= 2) {
                        this.passwordStrengthClass = 'bg-red-400';
                        this.passwordStrengthText = 'Weak';
                        this.passwordStrengthTextClass = 'text-red-500';
                    } else if (score === 3 || score === 4) {
                        this.passwordStrengthClass = 'bg-yellow-400';
                        this.passwordStrengthText = 'Medium';
                        this.passwordStrengthTextClass = 'text-yellow-600';
                    } else if (score === 5) {
                        this.passwordStrengthClass = 'bg-green-500';
                        this.passwordStrengthText = 'Strong';
                        this.passwordStrengthTextClass = 'text-green-600';
                    } else {
                        this.passwordStrengthClass = 'bg-gray-300';
                        this.passwordStrengthText = '';
                        this.passwordStrengthTextClass = 'text-gray-400';
                    }
                },
                get passwordStrengthPercent() {
                    return this.passwordStrength * 20;
                },
                submitForm(e) {
                    this.loading = true;
                    e.target.submit();
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const roleInputs = document.querySelectorAll('input[name="role"]');
            const schoolField = document.getElementById('schoolField');
            const schoolInput = document.getElementById('school');
            function toggleSchoolField() {
                const isStudent = document.querySelector('input[name="role"]:checked').value === 'student';
                schoolField.style.display = isStudent ? 'block' : 'none';
                schoolInput.required = isStudent;
            }
            roleInputs.forEach(input => {
                input.addEventListener('change', toggleSchoolField);
            });
            // Initial state
            toggleSchoolField();
        });
    </script>
</body>
</html> 