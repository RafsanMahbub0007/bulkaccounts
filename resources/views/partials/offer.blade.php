@if($system->offer_status ?? true)
<div class="w-full bg-gradient-to-r from-red-600 to-rose-500 text-white text-sm sm:text-base font-medium shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row items-center justify-between py-2 text-center sm:text-left">

            <!-- Offer Text -->
            <div class="flex items-center space-x-2">
                <span class="text-yellow-300 text-lg">🔥</span>
                <span>
                    {{ $system->offer_text ?? 'Get 20% OFF all accounts! Use code: PVAPRO20' }}
                </span>
            </div>

            <!-- Button (Optional) -->
            <a href="{{ route('pricing') }}"
               class="mt-2 sm:mt-0 inline-block bg-white text-red-600 font-semibold px-4 py-1.5 rounded-full shadow-md hover:bg-gray-100 transition">
               Shop Now →
            </a>

        </div>

    </div>
</div>
@endif
