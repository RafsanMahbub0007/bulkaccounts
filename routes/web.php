<?php

use App\Models\Order;
use App\Livewire\AboutDetails;
use App\Livewire\Cart;
use App\Livewire\Categorydetails;
use Illuminate\Support\Facades\Route;
use App\Livewire\Checkout;
use App\Livewire\Contact;
use App\Livewire\Home;
use App\Livewire\Products;
use App\Livewire\ProductDetails;
use App\Livewire\UserOrders;
use App\Livewire\UserOrderDetails;
use App\Livewire\Posts;
use App\Livewire\PostDetails;
use App\Livewire\SubCategoryDetails;
use App\Livewire\UserPayments;
use App\Livewire\Categories;
use App\Livewire\FaqDetails;
use App\Livewire\Guidelinedetails;
use App\Livewire\PrivacyPolicy;
use App\Livewire\Terms;

// Public Routes
Route::get('/', Home::class)->name('home');
Route::get('/pricing', Products::class)->name('pricing');
Route::get('/faq', FaqDetails::class)->name('faq');
Route::get('/about', AboutDetails::class)->name('about');
Route::get('/guidlines', Guidelinedetails::class)->name('guidlines');
Route::get('/blog', Posts::class)->name('blog');
Route::get('/terms', Terms::class)->name('terms');

Route::get('/posts/{post:slug}', PostDetails::class)->name('post.show');

Route::get('/cart', Cart::class)->name('cart');
Route::get('/checkout', Checkout::class)->name('checkout');
Route::get('/contact', Contact::class)->name('contact');
Route::view('/privacy-policy', PrivacyPolicy::class)->name('privacy');

// Protected Routes
Route::middleware(['auth','verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/user/orders', UserOrders::class)->name('user.orders');
    Route::get('/user/orders/{order:order_number}', UserOrderDetails::class)->name('user.orders.details');
    Route::get('/user/payments', UserPayments::class)->name('user.payments');
});

// Product Route
Route::get('/product/{product:slug}', ProductDetails::class)->name('product.details');

// Payment Routes
Route::get('/payment/success/{order}', function (Order $order) {
    $order->update(['payment_status' => 'paid', 'order_status' => 'confirmed']);
    return view('payment.success', compact('order'));
})->name('payment.success');

Route::get('/payment/cancel/{order}', function (Order $order) {
    $order->update(['payment_status' => 'failed']);
    return view('payment.cancel', compact('order'));
})->name('payment.cancel');

// Dynamic Category Routes — ALWAYS LAST
Route::get('/{category:slug}/{subcategory:slug}', SubCategoryDetails::class)->name('subcategory.details');
Route::get('/{category:slug}', Categorydetails::class)->name('category.details');
