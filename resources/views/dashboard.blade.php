<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Welcome message -->
                <div class="mb-8 text-center">
                    <h3 class="text-3xl font-semibold text-gray-800 dark:text-white">Welcome Back,
                        {{ auth()->user()->name }}!</h3>
                    <p class="mt-2 text-lg text-gray-600">Here are some quick links to manage your account:</p>
                </div>

                <!-- Dashboard Links -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                    <!-- Orders Link -->
                    <div class="bg-green-500 text-white rounded-lg p-6 shadow-lg flex items-center space-x-4">
                        <i class="fas fa-box-open text-3xl"></i>
                        <div>
                            <h3 class="text-2xl font-semibold">Orders</h3>
                            <p class="text-lg mt-2">
                                <a href="{{ route('user.orders') }}" class="text-white underline">View Your Orders</a>
                            </p>
                        </div>
                    </div>

                    <!-- Payments Link -->
                    <div class="bg-yellow-500 text-white rounded-lg p-6 shadow-lg flex items-center space-x-4">
                        <i class="fas fa-credit-card text-3xl"></i>
                        <div>
                            <h3 class="text-2xl font-semibold">Payments</h3>
                            <p class="text-lg mt-2">
                                <a href="{{ route('user.payments') }}" class="text-white underline">View Payment
                                    History</a>
                            </p>
                        </div>
                    </div>

                    <!-- Profile Link -->
                    <div class="bg-blue-500 text-white rounded-lg p-6 shadow-lg flex items-center space-x-4">
                        <i class="fas fa-user-circle text-3xl"></i>
                        <div>
                            <h3 class="text-2xl font-semibold">Profile</h3>
                            <p class="text-lg mt-2">
                                <a href="{{ route('profile.show') }}" class="text-white underline">View or Edit
                                    Profile</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
