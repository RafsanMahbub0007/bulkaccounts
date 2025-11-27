        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 shadow-lg flex flex-col">
            <div class="p-6 flex items-center justify-center border-b border-gray-700">
                <h1 class="text-2xl font-bold text-white">
                   <a href="{{route('dashboard')}}">Dashboard</a> </h1>
            </div>

            <nav class="flex-1 mt-6 px-4 space-y-2">
                @if(auth()->user()->role_id == 2)
                    <a href="{{ route('user.orders') }}"
                       class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->is('user/orders*') ? 'bg-gray-700' : '' }}">
                       <i class="fas fa-box-open mr-2"></i> Orders
                    </a>
                    <a href="{{ route('user.payments') }}"
                       class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->is('user/payments*') ? 'bg-gray-700' : '' }}">
                       <i class="fas fa-credit-card mr-2"></i> Payments
                    </a>
                @elseif(auth()->user()->role_id == 3)
                    <a href="#"
                       class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->is('seller/orders*') ? 'bg-gray-700' : '' }}">
                       <i class="fas fa-shopping-cart mr-2"></i> Orders Received
                    </a>
                    <a href="#"
                       class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->is('seller/products*') ? 'bg-gray-700' : '' }}">
                       <i class="fas fa-boxes mr-2"></i> Products
                    </a>
                    <a href="#"
                       class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->is('seller/stats*') ? 'bg-gray-700' : '' }}">
                       <i class="fas fa-chart-line mr-2"></i> Statistics
                    </a>
                @endif

                <a href="#"
                   class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 {{ request()->is('profile*') ? 'bg-gray-700' : '' }}">
                   <i class="fas fa-user-circle mr-2"></i> Profile
                </a>
            </nav>

            <div class="p-6 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-3 rounded-lg hover:bg-red-600 transition-colors duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </aside>
