<div class="flex flex-col sm:flex-row gap-3 mt-4 pointer-events-auto">

    <!-- Add to Cart / Pre-Order -->
    <div class="flex flex-col flex-1">
        <span wire:click="addToCart"
            class="w-full
                flex items-center justify-center
                px-4 sm:px-5 py-2 sm:py-2.5
                text-[13px] sm:text-sm font-semibold
                whitespace-nowrap
                rounded-full
                bg-gradient-to-r {{ $isPreOrder ? 'from-cyan-500 to-blue-500' : 'from-pink-500 to-purple-500' }} text-white
                shadow-lg {{ $isPreOrder ? 'shadow-cyan-500/30' : 'shadow-pink-500/30' }}
                transition-all duration-300 cursor-pointer
                hover:scale-105 active:scale-95">
            {{ $isPreOrder ? '' : 'Add to Cart' }}
            @if($isPreOrder)
                <span class="flex items-center gap-2">
                    <i class="fas fa-rotate"></i> Pre-Order Now
                </span>
            @endif
        </span>
        @if($isPreOrder)
            <span class="text-[10px] sm:text-xs text-cyan-500 text-center mt-1 font-medium animate-pulse">
                Delivery: 24-72 hours
            </span>
        @endif
    </div>

    <!-- Buy Now -->
    @if(!$isPreOrder)
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
    @endif
</div>
