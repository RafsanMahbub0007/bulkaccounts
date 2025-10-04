<?php

use App\Livewire\Cart;
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
use App\Livewire\UserPayments;
use App\Livewire\Categories;

Route::get('/', Home::class)->name('home');
Route::get('/pricing', Products::class)->name('pricing');
Route::get('/category/{Category:slug}', Categories::class)->name('category.show');
Route::get('/product/{product:slug}', ProductDetails::class)->name('product.details');
Route::get('/blog', Posts::class)->name('blog');
Route::get('/posts/{post:slug}', PostDetails::class)->name('post.show');
Route::get('/cart', Cart::class)->name('cart');
Route::get('/checkout', Checkout::class)->name('checkout');
Route::get('/contact', Contact::class)->name('contact');



// Static Routes
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

Route::get('/terms-and-conditions', function () {
    return view('terms-and-conditions');
})->name('terms-and-conditions');

Route::get('/refund-policy', function () {
    return view('refund-policy');
})->name('refund-policy');


// Protected Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/user/orders', UserOrders::class)->name('user.orders');
    Route::get('/user/orders/{order:order_number}', UserOrderDetails::class)->name('user.orders.details');
    Route::get('/user/payments', UserPayments::class)->name('user.payments');
});
