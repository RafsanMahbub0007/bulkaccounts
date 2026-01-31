<div class="flex flex-col sm:flex-row gap-3 mt-4 pointer-events-auto">

    <!-- Add to Cart / Pre-Order -->
    <span wire:click="addToCart"
        class="flex-1 min-w-0
               flex items-center justify-center
               px-4 sm:px-5 py-2 sm:py-2.5
               text-[13px] sm:text-sm font-semibold
               whitespace-nowrap
               rounded-full
               bg-gradient-to-r {{ $isPreOrder ? 'from-amber-500 to-orange-500' : 'from-pink-500 to-purple-500' }} text-white
               shadow-lg {{ $isPreOrder ? 'shadow-amber-500/30' : 'shadow-pink-500/30' }}
               transition-all duration-300 cursor-pointer
               hover:scale-105 active:scale-95">
        {{ $isPreOrder ? 'Pre-Order Now' : 'Add to Cart' }}
    </span>

    <!-- Buy Now -->
    <span wire:click="buyNow"
        class="flex-1 min-w-0
               flex items-center justify-center
               px-4 sm:px-5 py-2 sm:py-2.5
               text-[13px] sm:text-sm font-semibold
               whitespace-nowrap
               rounded-full
               bg-gradient-to-r from-cyan-500 to-blue-500 text-white
               shadow-lg shadow-cyan-500/30
               transition-all duration-300 cursor-pointer
               hover:scale-105 active:scale-95">
        Buy&nbsp;Now
    </span>
</div>
