<section class="bg-gray-900 text-white px-6 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <!-- Back to Orders Button -->
            <a href="{{ route('user.orders') }}"
                class="px-4 py-2 flex items-center bg-red-600 text-white rounded-lg hover:bg-red-700">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>

            <!-- Pay Now Button -->
            @if ($order->payment_status !== 'paid')
                <button wire:click="togglePaymentModal"
                    class="px-4 py-2 flex items-center bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-credit-card mr-2"></i> Pay
                </button>
            @endif
        </div>

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-600 font-semibold text-center text-white rounded-lg shadow-md">
                {{ session('error') }}
            </div>
        @endif

        @if (session()->has('status'))
            <div class="mb-6 p-4 bg-green-600 font-semibold text-center text-white rounded-lg shadow-md">
                {{ session('status') }}
            </div>
        @endif

        @if ($order)
            <div x-data="{ activeTab: 'info' }" class="bg-gray-800 shadow-lg rounded-lg p-6 space-y-6">
                <!-- Tabs -->
                <div class="flex flex-col md:flex-row justify-center space-y-6 md:space-y-0 md:space-x-6">
                    <button @click="activeTab = 'info'"
                        :class="activeTab === 'info' ? 'bg-red-500 text-white' : 'bg-gray-700 text-gray-300'"
                        class="px-6 py-2 rounded-lg transition-colors">Order Info</button>
                    <button @click="activeTab = 'items'"
                        :class="activeTab === 'items' ? 'bg-red-500 text-white' : 'bg-gray-700 text-gray-300'"
                        class="px-6 py-2 rounded-lg transition-colors">Order Items</button>
                    <button @click="activeTab = 'payments'"
                        :class="activeTab === 'payments' ? 'bg-red-500 text-white' : 'bg-gray-700 text-gray-300'"
                        class="px-6 py-2 rounded-lg transition-colors">Payments</button>
                </div>

                <!-- Order Info Tab -->
                <div x-show="activeTab === 'info'" class="space-y-4">
                    <div class="flex justify-between">
                        <span class="font-semibold text-lg">Order Number:</span>
                        <span class="text-gray-300">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-lg">Total Price:</span>
                        <span class="text-gray-300">${{ number_format($order->total_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-lg">Order Status:</span>
                        <span
                            class="px-4 py-1 rounded-full text-white {{ $order->order_status === 'completed' ? 'bg-green-500' : ($order->order_status === 'cancelled' ? 'bg-red-500' : 'bg-yellow-500') }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-lg">Payment Status:</span>
                        <span
                            class="px-4 py-1 rounded-full text-white {{ $order->payment_status === 'paid' ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ Str::title(str_replace('_', ' ', $order->payment_status)) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-lg">Ordered At:</span>
                        <span
                            class="text-gray-300">{{ \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-lg">Completed At:</span>
                        <span class="text-gray-300">
                            @if ($order->completed_at)
                                {{ \Carbon\Carbon::parse($order->completed_at)->format('Y-m-d H:i') }}
                            @else
                                <span class="text-gray-500">Pending</span>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Order Items Tab -->
                <div x-show="activeTab === 'items'" class="space-y-6">

                    @foreach ($order->orderItems as $orderItem)
                        <div class="bg-gray-700 rounded-lg p-6 shadow-md space-y-4">

                            <div class="flex justify-between">
                                <span class="font-semibold">Product:</span>
                                <span class="text-gray-300">{{ $orderItem->product->name }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="font-semibold">Quantity:</span>
                                <span class="text-gray-300">{{ $orderItem->quantity }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="font-semibold">Unit Price:</span>
                                <span class="text-gray-300">${{ number_format($orderItem->unit_price, 2) }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="font-semibold">Total Price:</span>
                                <span class="text-gray-300">${{ number_format($orderItem->total_price, 2) }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="font-semibold">Accounts Assigned:</span>
                                <span class="text-gray-300">
                                    @if ($orderItem->deliveries->isNotEmpty())
                                        @foreach ($orderItem->deliveries as $delivery)
                                            {{ $delivery->accounts ?? 'N/A' }}<br>
                                        @endforeach
                                    @else
                                        Not Delivered Yet
                                    @endif
                                </span>
                            </div>

                            <!-- 🔥 Download Button -->
                            @if ($orderItem->download_file)
                                <a href="{{ route('order.download', $orderItem->id) }}"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-center block mt-4">
                                    <i class="fas fa-download mr-2"></i> Download Accounts File
                                </a>
                            @else
                                <span class="block text-center text-yellow-400 mt-4">
                                    File Not Generated Yet
                                </span>
                            @endif

                        </div>
                    @endforeach

                </div>


                <!-- Payments Tab -->
                <div x-show="activeTab === 'payments'" class="space-y-6">
                    @forelse ($order->payments as $payment)
                        <div class="bg-gray-700 rounded-lg p-6 shadow-md space-y-4">
                            <div class="flex justify-between">
                                <span class="font-semibold">Transaction ID:</span>
                                <span class="text-gray-300">{{ $payment->transaction_id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Amount:</span>
                                <span class="text-gray-300">${{ number_format($payment->amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Status:</span>
                                <span
                                    class="px-4 py-1 rounded-full text-white {{ $payment->status === 'completed' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Paid At:</span>
                                <span
                                    class="text-gray-300">{{ \Carbon\Carbon::parse($payment->paid_at)->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-500 text-center">No payments available for this order.</div>
                    @endforelse
                </div>
            </div>
        @else
            <div class="text-center text-gray-500 text-xl mt-8">No order details available.</div>
        @endif
    </div>

    <!-- Payment modal component -->
    <x-modal wire:model="payment_modal" :id="'custom-modal'" :maxWidth="'md'">
        <form wire:submit="pay" class="p-6 bg-gray-800 text-white">
            <div class="mb-4">
                <x-label for="payment_method" class="mb-2">Payment Method</x-label>
                <x-select wire:model="payment_method" id="payment_method" class="w-full bg-gray-700 text-white">
                    <option value="">Select Method</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="debit_card">Debit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="apple_pay">Apple Pay</option>
                    <option value="google_pay">Google Pay</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="cryptocurrency">Cryptocurrency</option>
                    <option value="cash_on_delivery">Cash on Delivery</option>
                    <option value="other">Other</option>
                </x-select>
            </div>

            <div class="mb-4">
                <x-label for="transaction_id" class="mb-2">Transaction ID</x-label>
                <x-input type="text" wire:model="transaction_id" id="transaction_id"
                    class="w-full bg-gray-700 text-white" />
            </div>

            <div class="mb-4">
                <x-label for="currency" class="mb-2">Currency</x-label>
                <x-input type="text" wire:model="currency" id="currency"
                    class="w-full bg-gray-700 text-white" />
            </div>

            <div class="mb-4">
                <x-label for="amount" class="mb-2">Amount</x-label>
                <x-input type="number" wire:model="amount" id="amount" step="0.01"
                    class="w-full bg-gray-700 text-white" />
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" wire:click="togglePaymentModal"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Pay</button>
            </div>
        </form>
    </x-modal>
</section>
