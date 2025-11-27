<x-app-layout>
    <div class="flex h-screen bg-gray-900 text-gray-100">
        <!-- Main Content -->
        <main class="flex-1 overflow-auto p-8">
            <!-- Role-Specific Section -->
            <div>
                @if(auth()->user()->role_id == 2)
                    @livewire('user-dashboard')
                @elseif(auth()->user()->role_id == 3)
                    @livewire('seller-dashboard')
                @endif
            </div>
        </main>
    </div>
</x-app-layout>
