<div>
    @php
        $seo = \App\Models\SeoSetting::where('page_name', 'checkout')->first();
        $title = $seo->meta_title ?? 'Secure Checkout';
        $description = $seo->meta_description ?? 'Complete your purchase securely. Instant delivery for verified accounts.';
        $keywords = $seo->meta_keywords ?? '';
    @endphp
    @section('title', $title)
    @section('description', $description)
    @section('keywords', $keywords)

    <div class="bg-gray-900 text-white py-16">
        <div class="container mx-auto px-6 max-w-6xl">

            <h1 class="text-4xl font-bold text-red-500 mb-12 text-center">Checkout</h1>

            @guest
                <div class="bg-gray-800 p-10 rounded-xl text-center">
                    <h2 class="text-2xl font-bold mb-3">Login Required</h2>
                    <p class="text-gray-400 mb-6">Please login to continue checkout.</p>
                    <a href="{{ route('login') }}" class="px-6 py-3 bg-red-600 rounded-lg text-white font-semibold">Login</a>
                    <a href="{{ route('register') }}" class="px-6 py-3 bg-gray-700 rounded-lg ml-3">Create Account</a>
                </div>
            @else
                <form class="grid grid-cols-1 lg:grid-cols-2 gap-12" wire:submit.prevent>

                    {{-- =========================
                        BILLING INFORMATION
                    ========================= --}}
                    <div class="bg-gray-800/70 p-8 rounded-xl border border-gray-700">

                        <h2 class="text-2xl font-semibold mb-6 border-b border-gray-700 pb-3">
                            Billing Information
                        </h2>

                        @if ($errors->isNotEmpty())
                            <div class="bg-red-600/90 p-4 rounded-lg mb-4 text-sm">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="space-y-5">
                            {{-- Full Name --}}
                            <div>
                                <label class="text-gray-300 block mb-1">Full Name</label>
                                <input type="text" wire:model="name"
                                    class="w-full bg-gray-700 px-4 py-3 rounded-lg focus:ring-red-500 focus:ring-2">
                                @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label class="text-gray-300 block mb-1">Phone</label>
                                <div class="flex gap-3">
                                    @php
                                        $ccList = collect($countryCodes ?? []);
                                        $ccSelected = $ccList->firstWhere('dial_code', $countryCode) ?? $ccList->first();
                                    @endphp
                                    <div class="relative w-40" data-country-code-root wire:ignore>
                                        <button type="button" data-country-code-toggle
                                            class="w-full bg-gray-700 px-3 py-3 rounded-lg focus:ring-red-500 focus:ring-2 text-white flex items-center justify-between gap-2">
                                            <span class="flex items-center gap-2 min-w-0">
                                                <img class="w-5 h-4 rounded-sm flex-none"
                                                    src="https://flagcdn.com/24x18/{{ strtolower($ccSelected['iso2'] ?? 'us') }}.png"
                                                    alt="{{ $ccSelected['name'] ?? 'Country' }}">
                                                <span class="truncate">({{ $countryCode }})</span>
                                            </span>
                                            <svg class="w-4 h-4 flex-none text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>

                                        <div data-country-code-menu
                                            class="hidden absolute z-50 mt-2 w-72 max-h-72 overflow-y-auto rounded-lg border border-white/10 bg-gray-800 shadow-2xl">
                                            <div class="sticky top-0 z-10 bg-gray-800 p-2 border-b border-white/10">
                                                <input type="text" data-country-code-search placeholder="Search country or code"
                                                    class="w-full bg-gray-700 px-3 py-2 rounded-md text-white placeholder:text-gray-400 focus:ring-red-500 focus:ring-2">
                                            </div>
                                            @foreach (($countryCodes ?? []) as $c)
                                                <button type="button"
                                                    data-country-code-option
                                                    data-dial="{{ $c['dial_code'] }}"
                                                    data-name="{{ strtolower($c['name']) }}"
                                                    data-iso="{{ strtolower($c['iso2']) }}"
                                                    class="w-full px-3 py-2 text-left hover:bg-white/5 flex items-center gap-3"
                                                    wire:click="$set('countryCode', '{{ $c['dial_code'] }}')">
                                                    <img class="w-5 h-4 rounded-sm flex-none"
                                                        src="https://flagcdn.com/24x18/{{ strtolower($c['iso2']) }}.png"
                                                        alt="{{ $c['name'] }}">
                                                    <span class="text-white">({{ $c['dial_code'] }})</span>
                                                    <span class="text-gray-400 text-sm truncate">{{ $c['name'] }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="tel" inputmode="tel" wire:model="number" placeholder="Phone number"
                                        class="flex-1 bg-gray-700 px-4 py-3 rounded-lg focus:ring-red-500 focus:ring-2">
                                </div>
                                @error('countryCode') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                                @error('number') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                                <p class="text-gray-500 text-xs mt-1">Phone is saved with country code (e.g., +1 5551234567).</p>
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="text-gray-300 block mb-1">Email</label>
                                <input type="email" wire:model="email"
                                    class="w-full bg-gray-700 px-4 py-3 rounded-lg focus:ring-red-500 focus:ring-2">
                                @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Payment Method --}}
                            <div>
                                <label class="text-gray-300 block mb-1">
                                    Payment Method <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="paymentMethod" required
                                    class="w-full bg-gray-700 px-4 py-3 rounded-lg focus:ring-red-500 focus:ring-2 border border-gray-600 text-white">
                                    <option value="" disabled>Select payment method</option>
                                    @foreach ($paymentMethods as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('paymentMethod') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                                <p class="text-gray-500 text-xs mt-1">
                                    NOWPayments gateway will open directly with the selected cryptocurrency.
                                    Each method has a minimum order amount listed in parentheses.
                                </p>
                            </div>

                            {{-- Terms --}}
                            <div class="flex items-start gap-3">
                                <input type="checkbox" wire:model="acceptedTerms" id="terms" class="mt-1 text-red-600 rounded">
                                <label for="terms" class="text-gray-300 text-sm">
                                    I agree to the
                                    <a href="{{ route('terms') }}" class="text-red-400 underline">Terms &amp; Conditions</a>
                                </label>
                            </div>
                            @error('acceptedTerms') <p class="text-red-400 text-sm">{{ $message }}</p> @enderror

                            {{-- Payment Button --}}
                            <div class="flex gap-3 pt-3">
                                @if ($isTestMode)
                                    <button type="button" wire:click="proceedToPayment" wire:loading.attr="disabled"
                                        class="flex-1 bg-gray-700 py-3 rounded-lg font-semibold disabled:opacity-50">
                                        <span wire:loading.remove>Manual Payment</span>
                                        <span wire:loading>Verifying...</span>
                                    </button>
                                @else
                                    <button type="button" wire:click="proceedToPayment" wire:loading.attr="disabled"
                                        class="flex-1 bg-red-600 py-3 rounded-lg font-semibold disabled:opacity-50">
                                        <span wire:loading.remove>Pay Online</span>
                                        <span wire:loading>Verifying minimum...</span>
                                    </button>
                                @endif
                            </div>

                            @error('cart') <p class="text-yellow-400 text-sm mt-2">{{ $message }}</p> @enderror
                            @error('checkout') <p class="text-red-400 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- =========================
                        ORDER SUMMARY
                    ========================= --}}
                    <div class="bg-gray-800/70 p-8 rounded-xl border border-gray-700 lg:sticky lg:top-20">
                        <h3 class="text-2xl font-semibold mb-6 border-b border-gray-700 pb-3">Order Summary</h3>

                        <div class="space-y-4">
                            @forelse ($cartItems as $item)
                                <div class="flex justify-between items-center bg-gray-900 p-4 rounded-lg">
                                    <div>
                                        <p class="font-semibold">{{ $item['name'] }}</p>
                                        <p class="text-sm text-gray-400">Qty: {{ $item['quantity'] }}</p>
                                    </div>
                                    <p class="text-red-500 font-semibold">
                                        ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-gray-400">Your cart is empty.</p>
                            @endforelse
                        </div>

                        <div class="border-t border-gray-700 mt-6 pt-6 flex justify-between text-xl font-bold">
                            <span>Total</span>
                            <span class="text-red-500">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </form>
            @endguest
        </div>
    </div>

    {{-- =========================
        PAYMENT MODAL
    ========================= --}}
    <div x-data="{ open: @entangle('showPaymentModal') }" x-show="open" x-transition x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
        <div class="bg-gray-800 rounded-xl w-full max-w-md p-6" @click.outside="open = false">

            <h3 class="text-xl font-semibold text-center text-red-400 mb-4">Confirm Payment</h3>

            @if ($isTestMode)
                <div class="mb-4">
                    <label class="text-gray-300 block mb-1">Transaction ID</label>
                    <input type="text" wire:model="transactionIdInput"
                        class="w-full bg-gray-700 px-4 py-2 rounded-lg">
                    @error('transactionIdInput') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            @else
                <p class="text-gray-300 text-sm mb-4 text-center">
                    You will be redirected to the payment gateway to complete your purchase.
                </p>
            @endif

            <div class="mb-6">
                <label class="text-gray-400 block mb-1">Amount</label>
                <input type="text" readonly value="${{ number_format($total, 2) }}"
                    class="w-full bg-gray-700 px-4 py-2 rounded-lg text-gray-400">
            </div>

            <div class="flex gap-3">
                <button type="button" @click="open=false" class="flex-1 bg-gray-600 py-2 rounded-lg">Cancel</button>
                <button type="button" wire:click="confirmPayment" wire:loading.attr="disabled"
                    class="flex-1 bg-red-600 py-2 rounded-lg font-semibold disabled:opacity-50">
                    <span wire:loading.remove>Confirm</span>
                    <span wire:loading>Processing...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- =========================
        SCRIPTS
    ========================= --}}
    <script>
        function initCountryCodeCheckout() {
            document.querySelectorAll('[data-country-code-root]').forEach(root => {
                const toggle = root.querySelector('[data-country-code-toggle]');
                const menu = root.querySelector('[data-country-code-menu]');
                const search = root.querySelector('[data-country-code-search]');
                const options = root.querySelectorAll('[data-country-code-option]');

                if (!toggle || !menu || root.dataset.bound === '1') return;
                root.dataset.bound = '1';

                toggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    menu.classList.toggle('hidden');
                    if (!menu.classList.contains('hidden')) setTimeout(() => search?.focus(), 50);
                });

                document.addEventListener('click', (e) => {
                    if (!menu.contains(e.target) && !toggle.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                });

                search?.addEventListener('input', () => {
                    const q = search.value.toLowerCase().trim();
                    options.forEach(opt => {
                        const hay = (opt.dataset.dial + opt.dataset.name + opt.dataset.iso).toLowerCase();
                        opt.style.display = (!q || hay.includes(q)) ? '' : 'none';
                    });
                });
            });
        }

        document.addEventListener('DOMContentLoaded', initCountryCodeCheckout);
        document.addEventListener('livewire:navigated', initCountryCodeCheckout);

        Livewire.on('alert', data => alert(data.message));
        Livewire.on('order-success', e => {
            alert(e.message);
            if (e.redirect) window.location.href = e.redirect;
        });
    </script>
</div>
