<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-900 via-indigo-900 to-black px-6 py-10">

        <div class="max-w-md w-full relative">

            <!-- Main Card with hover group -->
            <div class="relative bg-gray-900/60 backdrop-blur-2xl border border-white/10 rounded-3xl shadow-2xl p-10 group overflow-hidden">

                <!-- Decorative Glowing Blobs -->
                <div class="absolute -top-24 -left-24 w-60 h-60 bg-pink-500/20 rounded-full blur-3xl animate-blob-pulse"></div>
                <div class="absolute -bottom-28 -right-20 w-72 h-72 bg-cyan-400/20 rounded-full blur-3xl animate-blob-pulse animation-delay-2000"></div>

                <!-- Logo -->
                <div class="flex justify-center mb-8 relative z-10">
                    <x-authentication-card-logo class="w-20 h-20 drop-shadow-xl" />
                </div>

                <!-- Title -->
                <h2 class="text-center text-4xl font-extrabold text-white mb-8 tracking-wide relative z-10">
                    <span class="bg-gradient-to-r from-cyan-400 to-purple-500 bg-clip-text text-transparent">
                        Create Your Account
                    </span>
                </h2>

                <!-- Validation Errors -->
                <x-validation-errors class="mb-4 text-red-300 relative z-10" />

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-6 relative z-10">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-label for="name" value="Full Name" class="text-gray-300 font-semibold" />
                        <x-input id="name"
                                 class="mt-2 block w-full rounded-xl bg-gray-800/60 border border-gray-700 text-white placeholder-gray-500 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                 type="text"
                                 name="name"
                                 :value="old('name')"
                                 required autofocus placeholder="John Doe" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-label for="email" value="Email Address" class="text-gray-300 font-semibold" />
                        <x-input id="email"
                                 class="mt-2 block w-full rounded-xl bg-gray-800/60 border border-gray-700 text-white placeholder-gray-500 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                 type="email"
                                 name="email"
                                 :value="old('email')"
                                 required placeholder="you@example.com" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-label for="password" value="Password" class="text-gray-300 font-semibold" />
                        <x-input id="password"
                                 class="mt-2 block w-full rounded-xl bg-gray-800/60 border border-gray-700 text-white placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                 type="password"
                                 name="password"
                                 required placeholder="••••••••" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-label for="password_confirmation" value="Confirm Password" class="text-gray-300 font-semibold" />
                        <x-input id="password_confirmation"
                                 class="mt-2 block w-full rounded-xl bg-gray-800/60 border border-gray-700 text-white placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                 type="password"
                                 name="password_confirmation"
                                 required placeholder="••••••••" />
                    </div>

                    <!-- Role Selection -->
                    <div x-data="{ role: '2' }">
                        <x-label value="Register As" class="text-gray-300 font-semibold" />

                        <input type="hidden" name="role_id" x-model="role" />

                        <div class="grid grid-cols-2 gap-5 mt-3">

                            <!-- User -->
                            <div @click="role = '2'"
                                :class="role === '2'
                                    ? 'border-cyan-400/60 bg-gray-800/80 shadow-lg shadow-cyan-500/30 scale-[1.05] ring-2 ring-cyan-400/40'
                                    : 'border-gray-700 bg-gray-800/40 scale-100'"
                                class="cursor-pointer border rounded-2xl p-5 text-center transition-all duration-300 hover:scale-[1.03]">
                                <h3 class="text-white font-semibold text-lg">User</h3>
                                <p class="text-gray-400 text-sm mt-1">Regular Account</p>
                            </div>

                            <!-- Seller -->
                            <div @click="role = '3'"
                                :class="role === '3'
                                    ? 'border-pink-400/60 bg-gray-800/80 shadow-lg shadow-pink-500/30 scale-[1.05] ring-2 ring-pink-400/40'
                                    : 'border-gray-700 bg-gray-800/40 scale-100'"
                                class="cursor-pointer border rounded-2xl p-5 text-center transition-all duration-300 hover:scale-[1.03]">
                                <h3 class="text-white font-semibold text-lg">Seller</h3>
                                <p class="text-gray-400 text-sm mt-1">Sell your products</p>
                            </div>

                        </div>
                    </div>

                    <!-- Terms -->
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="flex items-start gap-3 text-gray-300 text-sm mt-2">
                            <x-checkbox id="terms" name="terms" required />
                            <label for="terms" class="leading-tight">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' =>
                                        '<a target="_blank" href="' . route('terms') . '" class="underline text-purple-400 hover:text-purple-300">Terms of Service</a>',
                                    'privacy_policy' =>
                                        '<a target="_blank" href="' . route('privacy') . '" class="underline text-purple-400 hover:text-purple-300">Privacy Policy</a>',
                                ]) !!}
                            </label>
                        </div>
                    @endif

                    <!-- Submit -->
                    <div class="pt-3">
                        <x-button
                            class="w-full py-3 rounded-xl text-white font-semibold text-lg bg-gradient-to-r from-purple-500 to-pink-500 hover:from-pink-500 hover:to-purple-500 transition-all duration-300 shadow-lg shadow-purple-500/30">
                            Create Account
                        </x-button>
                    </div>

                </form>

                <!-- Already Registered -->
                <div class="mt-6 text-center relative z-10">
                    <a href="{{ route('login') }}" class="text-gray-300 underline hover:text-white transition">
                        Already have an account? Login
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Custom Tailwind Animation -->
    <style>
        @keyframes blob-pulse {
            0%, 100% { transform: scale(1) translate(0,0); opacity: 0.7; }
            33% { transform: scale(1.1) translate(10px, -10px); opacity: 0.8; }
            66% { transform: scale(0.9) translate(-10px, 10px); opacity: 0.7; }
        }
        .animate-blob-pulse {
            animation: blob-pulse 8s infinite ease-in-out;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>

</x-guest-layout>
