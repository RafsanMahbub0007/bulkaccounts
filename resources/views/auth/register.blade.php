<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-900 via-indigo-900 to-black px-6">
        <div class="max-w-md w-full">
            <div class="bg-gray-900/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/10 relative overflow-hidden">

                <!-- Floating Accent Lights -->
                <div class="absolute -top-16 -left-16 w-40 h-40 bg-gradient-to-tr from-pink-500/30 to-transparent rounded-full blur-3xl animate-blob"></div>
                <div class="absolute -bottom-16 -right-16 w-48 h-48 bg-gradient-to-br from-cyan-400/30 to-transparent rounded-full blur-3xl animate-blob animation-delay-2000"></div>

                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <x-authentication-card-logo class="w-20 h-20" />
                </div>

                <h2 class="text-center text-3xl font-extrabold text-white mb-6 bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">
                    Create Your Account
                </h2>

                <!-- Validation Errors -->
                <x-validation-errors class="mb-4 text-red-400" />

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-label for="name" value="{{ __('Name') }}" class="text-gray-200" />
                        <x-input id="name" class="mt-1 w-full rounded-xl bg-gray-800/50 border border-gray-700 text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Your full name" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-label for="email" value="{{ __('Email') }}" class="text-gray-200" />
                        <x-input id="email" class="mt-1 w-full rounded-xl bg-gray-800/50 border border-gray-700 text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-label for="password" value="{{ __('Password') }}" class="text-gray-200" />
                        <x-input id="password" class="mt-1 w-full rounded-xl bg-gray-800/50 border border-gray-700 text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            type="password" name="password" required autocomplete="new-password" placeholder="********" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-gray-200" />
                        <x-input id="password_confirmation" class="mt-1 w-full rounded-xl bg-gray-800/50 border border-gray-700 text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            type="password" name="password_confirmation" required autocomplete="new-password" placeholder="********" />
                    </div>

                    <!-- Terms & Privacy -->
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="flex items-start mt-2 text-gray-300">
                            <x-checkbox name="terms" id="terms" required />
                            <label for="terms" class="ml-2 text-sm">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' =>
                                        '<a target="_blank" href="' . route('terms') . '" class="underline hover:text-purple-400">Terms of Service</a>',
                                    'privacy_policy' =>
                                        '<a target="_blank" href="' . route('privacy') . '" class="underline hover:text-purple-400">Privacy Policy</a>',
                                ]) !!}
                            </label>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <x-button class="w-full py-3 text-white font-bold text-lg rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 hover:from-pink-500 hover:to-purple-500 transition-all duration-300 shadow-lg shadow-pink-500/50">
                            {{ __('Register') }}
                        </x-button>
                    </div>
                </form>

                <!-- Already Registered -->
                <div class="mt-4 text-center text-gray-400">
                    <a href="{{ route('login') }}" class="underline hover:text-white transition duration-200">
                        Already registered? Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
