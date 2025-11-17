<div class="flex flex-col sm:flex-row gap-3 mt-4 pointer-events-auto">
    <!-- Add to Cart -->
    <span wire:click="addToCart"
          class="flex-1 text-center px-4 py-2 text-sm font-semibold rounded-xl
                 bg-gradient-to-r from-pink-500 to-purple-500 text-white
                 shadow-lg shadow-pink-500/30 transition duration-300 cursor-pointer">
        Add to Cart
    </span>

    <!-- Buy Now -->
    <span wire:click="buyNow"
          class="flex-1 text-center px-4 py-2 text-sm font-semibold rounded-xl
                 bg-gradient-to-r from-cyan-500 to-blue-500 text-white
                 shadow-lg shadow-cyan-500/30 transition duration-300 cursor-pointer">
        Buy Now
    </span>
</div>


