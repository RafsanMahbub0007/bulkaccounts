<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        @php
            $ccList = collect($countryCodes ?? []);
            $ccSelected = $ccList->firstWhere('dial_code', $countryCode) ?? $ccList->first();
        @endphp

        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden" wire:model.live="photo" x-ref="photo"
                    x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}"
                        class="rounded-full size-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                        x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required
                autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required
                autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) &&
                    !$this->user->hasVerifiedEmail())
                <p class="text-sm mt-2 dark:text-white">
                    {{ __('Your email address is unverified.') }}

                    <button type="button"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800"
                        wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        <!-- Phone -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="phone" value="{{ __('Phone Number') }}" />
            <div class="mt-1 flex gap-3" data-country-code-root>
                <input type="hidden" wire:model="countryCode">
                <div class="relative w-40" data-country-code-wrapper>
                    <button type="button" data-country-code-toggle
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-red-500 dark:focus:border-red-600 focus:ring-red-500 dark:focus:ring-red-600 rounded-md shadow-sm px-3 py-2 flex items-center justify-between gap-2">
                        <span class="flex items-center gap-2 min-w-0">
                            <img class="w-5 h-4 rounded-sm flex-none"
                                src="https://flagcdn.com/24x18/{{ strtolower($ccSelected['iso2'] ?? 'us') }}.png"
                                alt="{{ $ccSelected['name'] ?? 'Country' }}">
                            <span class="truncate text-sm dark:text-white">({{ $countryCode }})</span>
                        </span>
                        <svg class="w-4 h-4 flex-none text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div data-country-code-menu
                        class="hidden absolute z-50 mt-2 w-72 max-h-72 overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-2xl">
                        <div class="sticky top-0 z-10 bg-white dark:bg-gray-800 p-2 border-b border-gray-100 dark:border-gray-700">
                            <input type="text" data-country-code-search placeholder="Search country or code"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md px-3 py-2 text-sm dark:text-white placeholder:text-gray-400 focus:ring-red-500 focus:border-red-500">
                        </div>
                        @foreach (($countryCodes ?? []) as $c)
                            <button type="button"
                                data-country-code-option
                                data-dial="{{ $c['dial_code'] }}"
                                data-name="{{ strtolower($c['name']) }}"
                                data-iso="{{ strtolower($c['iso2']) }}"
                                class="w-full px-3 py-2 text-left hover:bg-gray-100 dark:hover:bg-white/5 flex items-center gap-3"
                                wire:click="$set('countryCode', '{{ $c['dial_code'] }}')">
                                <img class="w-5 h-4 rounded-sm flex-none"
                                    src="https://flagcdn.com/24x18/{{ strtolower($c['iso2']) }}.png"
                                    alt="{{ $c['name'] }}">
                                <span class="text-gray-900 dark:text-white">({{ $c['dial_code'] }})</span>
                                <span class="text-gray-500 dark:text-gray-400 text-sm truncate">{{ $c['name'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
                <x-input id="phoneNumber" type="tel" inputmode="tel" class="flex-1" wire:model="phoneNumber"
                    placeholder="Phone number" required autocomplete="tel" />
            </div>
            <x-input-error for="phone" class="mt-2" />
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Phone number must be saved with country code (e.g., +1 for USA, +44 for UK).
            </p>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>

<script>
document.addEventListener('livewire:navigated', initCountryCode);
document.addEventListener('DOMContentLoaded', initCountryCode);

function initCountryCode() {
    document.querySelectorAll('[data-country-code-root]').forEach(root => {
        const toggle = root.querySelector('[data-country-code-toggle]');
        const menu = root.querySelector('[data-country-code-menu]');
        const search = root.querySelector('[data-country-code-search]');
        const options = root.querySelectorAll('[data-country-code-option]');

        if (!toggle || !menu) return;

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
                const dial = opt.getAttribute('data-dial');
                if (dial) {
                    Livewire.dispatch('setCountryCodeProfile', { code: dial });
                    menu.classList.add('hidden');
                }
            });
        });
    });
}
</script>
