<a {{ $attributes->merge(['class' =>
    'relative inline-flex items-center px-4 py-2 rounded-3xl font-semibold text-sm  tracking-widest text-white
     overflow-hidden border border-red-500 shadow-lg
     bg-gradient-to-r from-red-500 via-pink-500 to-purple-600
     animate-gradient-wave transition-all duration-500 ease-in-out']) }}>
    {{ $slot }}
</a>

<style>
@keyframes gradient-wave {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.animate-gradient-wave {
    background-size: 200% 200%;
    animation: gradient-wave 4s ease infinite;
}
</style>


