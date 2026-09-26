<x-guest-layout>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-900 via-indigo-900 to-black px-6 py-10">

        <div class="max-w-md w-full relative">

            <!-- Main Card -->
            <div
                class="relative bg-gray-900/60 backdrop-blur-2xl border border-white/10 rounded-3xl shadow-2xl p-10 group overflow-hidden">

                <!-- Decorative Glowing Blobs -->
                <div class="absolute -top-24 -left-24 w-60 h-60 bg-pink-500/20 rounded-full blur-3xl animate-blob-pulse">
                </div>
                <div
                    class="absolute -bottom-28 -right-20 w-72 h-72 bg-cyan-400/20 rounded-full blur-3xl animate-blob-pulse animation-delay-2000">
                </div>

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
                            type="text" name="name" :value="old('name')" required autofocus placeholder="John Doe" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-label for="email" value="Email Address" class="text-gray-300 font-semibold" />
                        <x-input id="email"
                            class="mt-2 block w-full rounded-xl bg-gray-800/60 border border-gray-700 text-white placeholder-gray-500 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            type="email" name="email" :value="old('email')" required placeholder="you@example.com" />
                    </div>

                    <!-- Phone (with Country Code) -->
                    <div>
                        @php
                            $allCountries = config('country_codes', []);
                            $preferredIso2ByDial = [
                                '+1' => 'US', '+44' => 'GB', '+61' => 'AU',
                                '+7' => 'RU', '+91' => 'IN', '+880' => 'BD',
                            ];
                            $countryCodes = collect($allCountries)
                                ->filter(fn ($c) => is_array($c) && isset($c['dial_code'], $c['iso2']))
                                ->groupBy('dial_code')
                                ->map(function ($group, $dial) use ($preferredIso2ByDial) {
                                    $preferredIso2 = $preferredIso2ByDial[$dial] ?? null;
                                    if ($preferredIso2) {
                                        $match = $group->firstWhere('iso2', $preferredIso2);
                                        if ($match) {
                                            return [
                                                'dial_code' => $match['dial_code'],
                                                'iso2' => $match['iso2'],
                                                'name' => $match['name'] ?? $match['iso2'],
                                            ];
                                        }
                                    }
                                    $first = $group->first();
                                    return [
                                        'dial_code' => $first['dial_code'],
                                        'iso2' => $first['iso2'],
                                        'name' => $first['name'] ?? $first['iso2'],
                                    ];
                                })
                                ->values()
                                ->sortBy('dial_code')
                                ->values();

                            $oldDial = old('country_code', '+1');
                            $ccSelected = $countryCodes->firstWhere('dial_code', $oldDial) ?? $countryCodes->first();
                        @endphp

                        <x-label for="phone_number" value="Phone Number" class="text-gray-300 font-semibold" />

                        <input type="hidden" name="country_code" id="country_code" value="{{ old('country_code', '+1') }}">

                        <div class="mt-2 flex gap-3" data-register-cc-root>
                            <div class="relative w-40" data-register-cc-wrap>
                                <button type="button" data-register-cc-toggle
                                    class="w-full rounded-xl bg-gray-800/60 border border-gray-700 px-3 py-3 text-white flex items-center justify-between gap-2 focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    <span class="flex items-center gap-2 min-w-0">
                                        <img class="w-5 h-4 rounded-sm flex-none"
                                            id="register-cc-flag"
                                            src="https://flagcdn.com/24x18/{{ strtolower($ccSelected['iso2'] ?? 'us') }}.png"
                                            alt="{{ $ccSelected['name'] ?? 'Country' }}">
                                        <span class="truncate text-sm" id="register-cc-label">({{ $oldDial }})</span>
                                    </span>
                                    <svg class="w-4 h-4 flex-none text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div data-register-cc-menu
                                    class="hidden absolute z-50 mt-2 w-72 max-h-72 overflow-y-auto rounded-xl border border-white/10 bg-gray-800 shadow-2xl">
                                    <div class="sticky top-0 z-10 bg-gray-800 p-2 border-b border-white/10">
                                        <input type="text" data-register-cc-search placeholder="Search country or code"
                                            class="w-full bg-gray-900/80 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white placeholder:text-gray-500 focus:ring-2 focus:ring-cyan-500">
                                    </div>
                                    @foreach ($countryCodes as $c)
                                        <button type="button"
                                            data-register-cc-option
                                            data-dial="{{ $c['dial_code'] }}"
                                            data-name="{{ strtolower($c['name']) }}"
                                            data-iso="{{ strtolower($c['iso2']) }}"
                                            class="w-full px-3 py-2 text-left hover:bg-white/5 flex items-center gap-3">
                                            <img class="w-5 h-4 rounded-sm flex-none"
                                                src="https://flagcdn.com/24x18/{{ strtolower($c['iso2']) }}.png"
                                                alt="{{ $c['name'] }}">
                                            <span class="text-white">({{ $c['dial_code'] }})</span>
                                            <span class="text-gray-400 text-sm truncate">{{ $c['name'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <x-input id="phone_number"
                                class="flex-1 rounded-xl bg-gray-800/60 border border-gray-700 text-white placeholder-gray-500 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                type="tel" inputmode="tel" name="phone_number" :value="old('phone_number')"
                                required placeholder="Phone number" />
                        </div>
                        @error('country_code')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('phone_number')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">
                            Phone is saved with country code (e.g., +1 5551234567).
                        </p>
                    </div>

                    <!-- Password w/ Icon -->
                    <div class="relative">
                        <x-label for="password" value="Password" class="text-gray-300 font-semibold" />
                        <x-input id="password"
                            class="mt-2 block w-full rounded-xl bg-gray-800/60 border border-gray-700 text-white placeholder-gray-500 pr-12 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            type="password" name="password" required placeholder="••••••••" />

                        <!-- eye icon -->
                        <button type="button" onclick="toggleBothPasswords()"
                            class="absolute right-3 top-1/2 mt-4 transform -translate-y-1/2 text-gray-400 hover:text-gray-200">
                            <svg id="eyeIcon1" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943
              9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>

                    </div>

                    <!-- Confirm Password w/ Icon -->
                    <div class="relative">
                        <x-label for="password_confirmation" value="Confirm Password"
                            class="text-gray-300 font-semibold" />
                        <x-input id="password_confirmation"
                            class="mt-2 block w-full rounded-xl bg-gray-800/60 border border-gray-700 text-white placeholder-gray-500 pr-12 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            type="password" name="password_confirmation" required placeholder="••••••••" />

                        <!-- eye icon -->
                        <button type="button" onclick="toggleBothPasswords()"
                            class="absolute right-3 top-1/2 mt-4 transform -translate-y-1/2 text-gray-400 hover:text-gray-200">
                            <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943
              9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>

                    </div>

                    <!-- Role Selection -->
                    <div x-data="{ role: '2' }">
                        <x-label value="Register As" class="text-gray-300 font-semibold" />

                        <input type="hidden" name="role_id" x-model="role" />

                        <div class="grid grid-cols-2 gap-5 mt-3">

                            <!-- User -->
                            <div @click="role = '2'"
                                :class="role === '2'
                                    ?
                                    'border-cyan-400/60 bg-gray-800/80 shadow-lg shadow-cyan-500/30 scale-[1.05] ring-2 ring-cyan-400/40' :
                                    'border-gray-700 bg-gray-800/40 scale-100'"
                                class="cursor-pointer border rounded-2xl p-5 text-center transition-all duration-300 hover:scale-[1.03]">
                                <h3 class="text-white font-semibold text-lg">User</h3>
                                <p class="text-gray-400 text-sm mt-1">Regular Account</p>
                            </div>

                            <!-- Seller -->
                            <div @click="role = '3'"
                                :class="role === '3'
                                    ?
                                    'border-pink-400/60 bg-gray-800/80 shadow-lg shadow-pink-500/30 scale-[1.05] ring-2 ring-pink-400/40' :
                                    'border-gray-700 bg-gray-800/40 scale-100'"
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
                                        '<a target="_blank" href="' .
                                        route('terms') .
                                        '" class="underline text-purple-400 hover:text-purple-300">Terms of Service</a>',
                                    'privacy_policy' =>
                                        '<a target="_blank" href="' .
                                        route('privacy') .
                                        '" class="underline text-purple-400 hover:text-purple-300">Privacy Policy</a>',
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

    <!-- Password Toggle Script -->
    <script>
        function toggleBothPasswords() {
            const pw1 = document.getElementById('password');
            const pw2 = document.getElementById('password_confirmation');
            const icon1 = document.getElementById('eyeIcon1');
            const icon2 = document.getElementById('eyeIcon2');

            const isHidden = pw1.type === "password";

            // Toggle both fields
            pw1.type = isHidden ? "text" : "password";
            pw2.type = isHidden ? "text" : "password";

            const iconOpen = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7
                     a10.04 10.04 0 011.563-3.029M6.18 6.18A9.953 9.953 0 0112 5c4.477 0
                     8.268 2.943 9.542 7a10.05 10.05 0 01-4.152 5.411M10.584 10.584a3 3 0
                     104.243 4.243" />
            <line x1="4" y1="4" x2="20" y2="20" stroke-width="2" stroke-linecap="round" />
        `;

            const iconClosed = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943
                  9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;

            // Update both icons
            icon1.innerHTML = isHidden ? iconOpen : iconClosed;
            icon2.innerHTML = isHidden ? iconOpen : iconClosed;
        }
    </script>

    <!-- Country Code Dropdown (Register) -->
    <script>
        document.addEventListener('DOMContentLoaded', initRegisterCountryCode);
        document.addEventListener('livewire:navigated', initRegisterCountryCode);

        function initRegisterCountryCode() {
            const root = document.querySelector('[data-register-cc-root]');
            if (!root) return;

            const toggle = root.querySelector('[data-register-cc-toggle]');
            const menu = root.querySelector('[data-register-cc-menu]');
            const search = root.querySelector('[data-register-cc-search]');
            const options = root.querySelectorAll('[data-register-cc-option]');
            const hiddenInput = document.getElementById('country_code');
            const labelEl = document.getElementById('register-cc-label');
            const flagEl = document.getElementById('register-cc-flag');

            if (!toggle || !menu || !hiddenInput) return;

            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                menu.classList.toggle('hidden');
                if (!menu.classList.contains('hidden')) {
                    setTimeout(() => search?.focus(), 50);
                }
            });

            document.addEventListener('click', (e) => {
                if (!menu.contains(e.target) && !toggle.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });

            if (search) {
                search.addEventListener('input', () => {
                    const q = search.value.toLowerCase().trim();
                    options.forEach(opt => {
                        const dial = opt.dataset.dial || '';
                        const name = opt.dataset.name || '';
                        const iso = opt.dataset.iso || '';
                        const show = !q || dial.includes(q) || name.includes(q) || iso.includes(q);
                        opt.style.display = show ? '' : 'none';
                    });
                });
            }

            options.forEach(opt => {
                opt.addEventListener('click', () => {
                    const dial = opt.dataset.dial;
                    const iso = opt.dataset.iso;
                    if (!dial) return;

                    hiddenInput.value = dial;
                    if (labelEl) labelEl.textContent = '(' + dial + ')';
                    if (flagEl) {
                        flagEl.src = 'https://flagcdn.com/24x18/' + (iso || 'us') + '.png';
                        flagEl.alt = dial;
                    }

                    menu.classList.add('hidden');
                });
            });
        }
    </script>


    <!-- Custom Animation -->
    <style>
        @keyframes blob-pulse {

            0%,
            100% {
                transform: scale(1) translate(0, 0);
                opacity: 0.7;
            }

            33% {
                transform: scale(1.1) translate(10px, -10px);
                opacity: 0.8;
            }

            66% {
                transform: scale(0.9) translate(-10px, 10px);
                opacity: 0.7;
            }
        }

        .animate-blob-pulse {
            animation: blob-pulse 8s infinite ease-in-out;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>

</x-guest-layout>
