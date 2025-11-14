<div>
    <div x-data="{ open: false }" class="relative">
        <!-- Floating Cart Button with Glowing Badge -->
        <a href="{{route('cart')}}"
            class="fixed right-4 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-purple-700 to-pink-600 text-white p-5 rounded-full shadow-2xl hover:scale-110 hover:from-pink-600 hover:to-purple-700 transition-all duration-300 ease-out">
            <i class="fas fa-shopping-cart fa-lg"></i>
            <span
                class="absolute -top-2 -right-2 bg-yellow-300 text-purple-800 font-bold text-xs w-6 h-6 flex items-center justify-center rounded-full shadow-md border border-yellow-500">
                {{ $totalCarts }}
            </span>
        </a>
    </div>

    <!-- Optional Custom Scrollbar -->
    <style>
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #a855f7, #ec4899);
            border-radius: 4px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>
</div>
