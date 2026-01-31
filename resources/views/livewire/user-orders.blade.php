<section class="bg-gray-900 text-white py-8 px-6">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your Orders') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <!-- Confirmation Message -->
        @if (session()->has('success'))
            <div class="my-12 p-6 bg-green-600 text-white rounded-lg shadow-lg text-center">
                <h2 class="text-xl font-semibold">Thank you for your order!</h2>
                <p class="mt-2">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Search Bar with Sort Options -->
        <div class="mb-6 flex items-center justify-between space-x-4">
            <div>
                <x-input type="text" wire:model.live.debounce.250ms="search" placeholder="Search Orders..." />
            </div>
            <div>
                <x-select wire:model.live="sortBy">
                    <option value="ordered_at">Order Date</option>
                    <option value="total_price">Total Price</option>
                    <option value="status">Status</option>
                </x-select>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex space-x-4 mb-6 border-b border-gray-700">
            <button wire:click="switchTab('orders')" 
                class="pb-2 px-4 text-lg font-semibold transition-colors duration-200 border-b-2 {{ $activeTab === 'orders' ? 'border-red-600 text-white' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                Regular Orders
            </button>
            <button wire:click="switchTab('pre-orders')" 
                class="pb-2 px-4 text-lg font-semibold transition-colors duration-200 border-b-2 {{ $activeTab === 'pre-orders' ? 'border-red-600 text-white' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
                Pre-Orders
            </button>
        </div>

        @if ($activeTab === 'orders')
            @if ($orders->isEmpty())
                <div class="text-center text-gray-500 text-xl">You have no regular orders yet.</div>
            @else
                <div class="rounded-lg shadow-lg border border-gray-700">
                    <div class="md:hidden space-y-4 p-4">
                        @foreach ($orders as $order)
                            <div class="bg-gray-800 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-400">Order #</span>
                                    <span class="font-semibold">{{ $order->order_number }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm text-gray-400">Total</span>
                                    <span class="text-red-400 font-bold">${{ number_format($order->total_price, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm text-gray-400">Status</span>
                                    <span class="px-2 py-1 rounded-full text-white {{ $order->order_status === 'completed' ? 'bg-green-500' : 'bg-yellow-500' }}">{{ ucfirst($order->order_status) }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm text-gray-400">Payment</span>
                                    <span class="px-2 py-1 rounded-full text-white {{ $order->payment_status === 'paid' ? 'bg-green-500' : 'bg-red-500' }}">{{ ucfirst($order->payment_status) }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm text-gray-400">Ordered</span>
                                    <span>{{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d H:i') : 'N/A' }}</span>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('user.orders.details', $order) }}" class="block w-full text-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">View</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-red-600 text-white text-sm uppercase">
                                <tr>
                                    <th class="px-6 py-4 text-left">Order Number</th>
                                    <th class="px-6 py-4 text-left">Total Price</th>
                                    <th class="px-6 py-4 text-left">Order Status</th>
                                    <th class="px-6 py-4 text-left">Payment Status</th>
                                    <th class="px-6 py-4 text-left">Ordered At</th>
                                    <th class="px-6 py-4 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-400">
                                @foreach ($orders as $order)
                                    <tr class="border-b hover:bg-gray-800 transition-colors">
                                        <td class="px-6 py-4 font-semibold">{{ $order->order_number }}</td>
                                        <td class="px-6 py-4">${{ number_format($order->total_price, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-white {{ $order->order_status === 'completed' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                                {{ ucfirst($order->order_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-white {{ $order->payment_status === 'paid' ? 'bg-green-500' : 'bg-red-500' }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d H:i') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('user.orders.details', $order) }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                                                <i class="fas fa-eye mr-2"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @else
            {{-- Pre-Orders Tab Content --}}
            @if ($preOrders->isEmpty())
                <div class="text-center text-gray-500 text-xl">You have no pre-orders yet.</div>
            @else
                <div class="rounded-lg shadow-lg border border-gray-700">
                    <div class="md:hidden space-y-4 p-4">
                        @foreach ($preOrders as $order)
                            <div class="bg-gray-800 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-400">Order #</span>
                                    <span class="font-semibold">{{ $order->order_number }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm text-gray-400">Total</span>
                                    <span class="text-red-400 font-bold">${{ number_format($order->total_price, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm text-gray-400">Status</span>
                                    <span class="px-2 py-1 rounded-full text-white {{ $order->status === 'completed' ? 'bg-green-500' : 'bg-yellow-500' }}">{{ ucfirst($order->status) }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm text-gray-400">Payment</span>
                                    <span class="px-2 py-1 rounded-full text-white {{ $order->payment_status === 'paid' ? 'bg-green-500' : 'bg-red-500' }}">{{ ucfirst($order->payment_status) }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm text-gray-400">Ordered</span>
                                    <span>{{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d H:i') : 'N/A' }}</span>
                                </div>
                                @if($order->status === 'completed' && $order->download_file)
                                <div class="mt-3">
                                    <a href="{{ route('pre-order.download', $order) }}" class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Download</a>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-red-600 text-white text-sm uppercase">
                                <tr>
                                    <th class="px-6 py-4 text-left">Order Number</th>
                                    <th class="px-6 py-4 text-left">Total Price</th>
                                    <th class="px-6 py-4 text-left">Order Status</th>
                                    <th class="px-6 py-4 text-left">Payment Status</th>
                                    <th class="px-6 py-4 text-left">Ordered At</th>
                                    <th class="px-6 py-4 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-400">
                                @foreach ($preOrders as $order)
                                    <tr class="border-b hover:bg-gray-800 transition-colors">
                                        <td class="px-6 py-4 font-semibold">{{ $order->order_number }}</td>
                                        <td class="px-6 py-4">${{ number_format($order->total_price, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-white {{ $order->status === 'completed' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-white {{ $order->payment_status === 'paid' ? 'bg-green-500' : 'bg-red-500' }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d H:i') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($order->status === 'completed' && $order->download_file)
                                                <a href="{{ route('pre-order.download', $order) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                                                    <i class="fas fa-download mr-2"></i> Download
                                                </a>
                                            @else
                                                <span class="text-gray-500 italic">Processing</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endif
    </div>
</section>
