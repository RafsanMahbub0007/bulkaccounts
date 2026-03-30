@props(['on', 'type' => 'success'])

<div x-data="{
    shown: false,
    timeout: null,
    message: '{{ $slot->isEmpty() ? 'Saved successfully!' : $slot }}',
    type: '{{ $type }}'
}" x-init="window.addEventListener('{{ $on }}', (event) => {
    clearTimeout(timeout);
    if (event.detail && event.detail.message) {
        message = event.detail.message;
    }
    if (event.detail && event.detail.type) {
        type = event.detail.type;
    }
    shown = true;
    timeout = setTimeout(() => {
        shown = false
    }, 3000);
})" x-show.transition.out.opacity.duration.1500ms="shown"
    x-transition:leave.opacity.duration.1500ms style="display: none;"
    {{ $attributes->merge(['class' => 'fixed z-50 top-5 right-5 w-full sm:w-80 max-w-sm p-4 rounded-lg shadow-xl transition-all transform-gpu ease-in-out duration-300']) }}>

    <!-- Success Variant -->
    <div x-show="type === 'success'" x-cloak
        class="flex items-center space-x-3 bg-green-600 text-white rounded-lg p-4 shadow-lg hover:shadow-2xl transform transition-all duration-300">
        <div class="flex-shrink-0">
            <i class="fas fa-check-circle text-2xl text-white"></i>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold" x-text="message"></p>
        </div>
        <button @click="shown = false" class="flex-shrink-0 text-white hover:text-gray-300 focus:outline-none">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Failed Variant -->
    <div x-show="type === 'failed'" x-cloak
        class="flex items-center space-x-3 bg-red-600 text-white rounded-lg p-4 shadow-lg hover:shadow-2xl transform transition-all duration-300">
        <div class="flex-shrink-0">
            <i class="fas fa-times-circle text-2xl text-white"></i>
        </div>
        <div class="flex-1">
            <p class="text-sm font-semibold" x-text="message"></p>
        </div>
        <button @click="shown = false" class="flex-shrink-0 text-white hover:text-gray-300 focus:outline-none">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
