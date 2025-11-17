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
                <div class="mb-6">
                    <x-label for="password" value="{{ __('Password') }}" class="text-gray-300 mb-2 block" />
                    <x-input id="password"
                        class="w-full bg-gray-800/50 text-gray-100 border-gray-600 placeholder-gray-400 rounded-xl focus:ring-pink-500 focus:border-pink-500"
                        type="password" name="password" required autocomplete="current-password" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mb-4">
                    <x-checkbox id="remember_me" name="remember" class="focus:ring-pink-500" />
                    <label for="remember_me" class="ml-2 text-gray-400">{{ __('Remember me') }}</label>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between mt-6">
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
            </form>
        </div>
    </div>
</x-guest-layout>
