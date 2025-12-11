<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-black via-gray-900 to-gray-800 relative overflow-hidden">

        <!-- Background Stars -->
        <div class="absolute inset-0 bg-[url('/images/stars.png')] bg-cover opacity-20"></div>

        <!-- Glowing Circles -->
        <div class="absolute top-20 left-20 w-96 h-96 bg-purple-800/30 rounded-full blur-[160px]"></div>
        <div class="absolute bottom-20 right-20 w-72 h-72 bg-indigo-700/20 rounded-full blur-[120px]"></div>

        <!-- Login Card -->
        <div class="relative z-10 w-full max-w-md px-8 py-12 bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl shadow-2xl">

            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <x-authentication-card-logo class="w-20 h-20 drop-shadow-md" />
            </div>

            <!-- Title -->
            <h2 class="text-center text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-300 to-pink-400 mb-8 tracking-wide">
                Let's Start
            </h2>

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4 text-red-400" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-6">
                    <x-label for="email" value="{{ __('Email Address') }}" class="text-gray-300 mb-2 block" />
                    <x-input id="email"
                        class="w-full bg-gray-800/50 text-gray-100 border-gray-600 placeholder-gray-400 rounded-xl focus:ring-pink-500 focus:border-pink-500"
                        type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                </div>

                <!-- Password -->
                <div class="mb-6 relative">
                    <x-label for="password" value="{{ __('Password') }}" class="text-gray-300 mb-2 block" />
                    <x-input id="password"
                        class="w-full bg-gray-800/50 text-gray-100 border-gray-600 placeholder-gray-400 rounded-xl pr-12
                               focus:ring-pink-500 focus:border-pink-500"
                        type="password" name="password" required autocomplete="current-password" />

                    <!-- Show/Hide Icon -->
                    <button type="button" onclick="togglePassword()"
                        class="absolute right-3 top-10 transform -translate-y-2/2 text-gray-400 hover:text-gray-200">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065
                                7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mb-4">
                    <x-checkbox id="remember_me" name="remember" class="focus:ring-pink-500" />
                    <label for="remember_me" class="ml-2 text-gray-400">{{ __('Remember me') }}</label>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-3 mt-6">

                    <div class="flex items-center justify-between">
                        @if (Route::has('password.request'))
                            <a class="text-sm text-pink-300 hover:text-pink-200 hover:underline"
                                href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif

                        <x-button class="bg-gradient-to-br from-pink-500 to-purple-600 px-6 py-2 text-white shadow-lg rounded-xl
                                        hover:from-pink-400 hover:to-purple-500 transition-all duration-300 ease-out">
                            {{ __('Log in') }}
                        </x-button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center mt-2">
                        <a href="{{ route('register') }}"
                           class="text-sm text-purple-300 hover:text-purple-200 hover:underline">
                            Don't have an account? Create one
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Password Toggle Script -->
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');

            if (password.type === "password") {
                password.type = "text";
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7
                           a10.04 10.04 0 011.563-3.029M6.18 6.18A9.953 9.953 0 0112 5c4.477 0
                           8.268 2.943 9.542 7a10.05 10.05 0 01-4.152 5.411M10.584 10.584a3 3 0
                           104.243 4.243" />
                    <line x1="4" y1="4" x2="20" y2="20" stroke-width="2" stroke-linecap="round" />
                `;
            } else {
                password.type = "password";
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943
                        9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</x-guest-layout>
