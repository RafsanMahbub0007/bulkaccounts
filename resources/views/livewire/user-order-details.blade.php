<section class="bg-gray-900 min-h-screen px-6 py-12">
    <div class="max-w-7xl mx-auto">

        <!-- Back Button -->
        <div class="flex justify-start mb-8">
            <a href="{{ route('user.orders') }}"
               class="px-4 py-2 flex items-center bg-red-600 bg-opacity-80 backdrop-blur-md rounded-lg hover:bg-red-700 transition">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>

        @if ($order)
        <div class="space-y-10">

            <!-- ============================= -->
            <!--       ORDER INFO             -->
            <!-- ============================= -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl shadow-lg p-8 space-y-6">
                <h2 class="text-3xl font-bold text-white mb-4">Order Information</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Order Number -->
                    <div class="flex items-center bg-white/5 p-4 rounded-lg">
                        <i class="fas fa-hashtag text-red-400 mr-3"></i>
                        <div>
                            <p class="text-gray-300 font-semibold">Order Number</p>
                            <p class="text-white">{{ $order->order_number }}</p>
                        </div>
                    </div>

                    <!-- Total Price -->
                    <div class="flex items-center bg-white/5 p-4 rounded-lg">
                        <i class="fas fa-dollar-sign text-green-400 mr-3"></i>
                        <div>
                            <p class="text-gray-300 font-semibold">Total Price</p>
                            <p class="text-white">${{ number_format($order->total_price,2) }}</p>
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="flex items-center bg-white/5 p-4 rounded-lg">
                        <i class="fas fa-box text-yellow-400 mr-3"></i>
                        <div>
                            <p class="text-gray-300 font-semibold">Order Status</p>
                            <span class="px-4 py-1 rounded-full text-white
                                {{ $order->order_status==='completed'?'bg-green-500':($order->order_status==='cancelled'?'bg-red-500':'bg-yellow-500') }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="flex items-center bg-white/5 p-4 rounded-lg">
                        <i class="fas fa-credit-card text-blue-400 mr-3"></i>
                        <div>
                            <p class="text-gray-300 font-semibold">Payment Status</p>
                            <span class="px-4 py-1 rounded-full text-white
                                {{ $order->payment_status==='paid'?'bg-green-500':'bg-red-500' }}">
                                {{ Str::title(str_replace('_',' ',$order->payment_status)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Ordered At -->
                    <div class="flex items-center bg-white/5 p-4 rounded-lg">
                        <i class="fas fa-calendar-alt text-purple-400 mr-3"></i>
                        <div>
                            <p class="text-gray-300 font-semibold">Ordered At</p>
                            <p class="text-white">{{ \Carbon\Carbon::parse($order->ordered_at)->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>

                    <!-- Completed At -->
                    <div class="flex items-center bg-white/5 p-4 rounded-lg">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        <div>
                            <p class="text-gray-300 font-semibold">Completed At</p>
                            <p class="text-white">
                                @if($order->completed_at)
                                    {{ \Carbon\Carbon::parse($order->completed_at)->format('Y-m-d H:i') }}
                                @else
                                    <span class="text-yellow-400">Pending</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================= -->
            <!--       HORIZONTAL TIMELINE     -->
            <!-- ============================= -->
            @php
                $stages = [
                    ['label'=>'Ordered','icon'=>'fas fa-shopping-cart','status'=>'ordered'],
                    ['label'=>'Paid','icon'=>'fas fa-credit-card','status'=>'paid'],
                    ['label'=>'Completed','icon'=>'fas fa-check-circle','status'=>'completed']
                ];
                $progress = 0;
                if($order->payment_status==='paid') $progress=1;
                if($order->order_status==='completed') $progress=2;
                $stageCount = count($stages);
                $progressWidth = ($stageCount>1)?($progress/($stageCount-1))*100:0;
            @endphp

            <div class="bg-white/10 backdrop-blur-md rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-6 text-white">Order Timeline</h3>

                <div class="relative flex items-center justify-between">
                    <!-- Line behind -->
                    <div class="absolute top-5 left-0 w-full h-1 bg-gray-700 rounded z-0"></div>
                    <div class="absolute top-5 left-0 h-1 bg-green-400 rounded z-10 transition-all duration-1000" style="width: {{ $progressWidth }}%"></div>

                    @foreach($stages as $index=>$stage)
                        <div class="flex flex-col items-center relative z-20">
                            <div class="w-12 h-12 flex items-center justify-center rounded-full
                                transition-transform duration-500
                                {{ $index <= $progress ? 'bg-green-500 text-white scale-110' : 'bg-gray-600 text-gray-300' }}">
                                <i class="{{ $stage['icon'] }}"></i>
                            </div>
                            <span class="text-white mt-2 text-sm font-medium">{{ $stage['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ============================= -->
            <!--       ORDER ITEMS             -->
            <!-- ============================= -->
            <div class="space-y-4">
                <h2 class="text-3xl font-bold text-white mb-4">Order Items</h2>

                @foreach($order->orderItems as $item)
                <div class="bg-white/10 backdrop-blur-md rounded-xl shadow-md flex flex-col md:flex-row items-center p-6 space-y-4 md:space-y-0 md:space-x-6 hover:scale-105 transition-transform duration-300">
                    <div class="w-32 h-32 flex-shrink-0">
                        <img src="{{ image_path($item->product->subcategory->image) ?? 'https://via.placeholder.com/150' }}" alt="{{ $item->product->name }}" class="rounded-lg object-cover w-full h-full shadow-md">
                    </div>
                    <div class="flex-1 space-y-1">
                        <h3 class="text-xl font-semibold text-white">{{ $item->product->name }}</h3>
                        <p class="text-gray-300">Quantity: {{ $item->quantity }}</p>
                        <p class="text-gray-300">Unit Price: ${{ number_format($item->unit_price,2) }}</p>
                        <p class="text-gray-300 font-semibold">Total: ${{ number_format($item->total_price,2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- ============================= -->
            <!--       DOWNLOAD BUTTON         -->
            <!-- ============================= -->
            <div class="text-center mt-8">
                @if($order->payment_status==='paid' && $order->order_status==='completed' && $order->completed_at)
                    <a href="{{ route('order.download', $order->id) }}"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-lg text-lg transition">
                        <i class="fas fa-download mr-2"></i> Download Accounts File
                    </a>
                @else
                    <p class="text-yellow-400 text-lg">File Not Generated Yet</p>
                @endif
            </div>

        </div>
        @else
            <p class="text-center text-gray-500 text-xl mt-8">No order details available.</p>
        @endif
    </div>
</section>
