<section class="bg-gray-900 text-white py-8 px-6">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your Payments') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <!-- Confirmation Message -->
        @if (session()->has('success'))
            <div class="my-12 p-6 bg-green-600 text-white rounded-lg shadow-lg text-center">
                <h2 class="text-xl font-semibold">Payment Successful!</h2>
                <p class="mt-2">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Search Bar with Sort Options -->
        <div class="mb-6 flex items-center justify-between space-x-4">
            <div>
                <x-input type="text" wire:model.live.debounce.250ms="search" placeholder="Search Payments..." />
            </div>
            <div>
                <x-select wire:model.live="sortBy">
                    <option value="paid_at">Payment Date</option>
                    <option value="amount">Amount</option>
                    <option value="status">Status</option>
                </x-select>
            </div>
        </div>

        <!-- Payments Table -->
        @if ($payments->isEmpty())
            <div class="text-center text-gray-500 text-xl">No payments found.</div>
        @else
            <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-700">
                <table class="min-w-full table-auto">
                    <thead class="bg-red-600 text-white text-sm uppercase">
                        <tr>
                            <th class="px-6 py-4 text-left">Transaction ID</th>
                            <th class="px-6 py-4 text-left">Amount</th>
                            <th class="px-6 py-4 text-left">Currency</th>
                            <th class="px-6 py-4 text-left">Order Number</th>
                            <th class="px-6 py-4 text-left">Paid At</th>
                            <th class="px-6 py-4 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-400">
                        @foreach ($payments as $payment)
                            <tr class="border-b hover:bg-gray-800 transition-colors">
                                <td class="px-6 py-4">{{ $payment->transaction_id }}</td>
                                <td class="px-6 py-4">${{ number_format($payment->amount, 2) }}</td>
                                <td class="px-6 py-4">{{ $payment->currency }}</td>
                                <td class="px-6 py-4">{{ $payment->order_number }}</td>
                                <td class="px-6 py-4">
                                    {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-lg text-white {{ $payment->status === 'completed' ? 'bg-green-500' : 'bg-red-500' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $payments->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</section>
