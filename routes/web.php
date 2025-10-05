<?php

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

// Public Routes
Route::get('/', Home::class)->name('home');
Route::get('/pricing', Products::class)->name('pricing');
Route::get('/faq', FaqDetails::class)->name('faq');
Route::get('/about', AboutDetails::class)->name('about');
Route::get('/blog', Posts::class)->name('blog');
Route::get('/posts/{post:slug}', PostDetails::class)->name('post.show');

Route::get('/cart', Cart::class)->name('cart');
Route::get('/checkout', Checkout::class)->name('checkout');
Route::get('/contact', Contact::class)->name('contact');

// Product Route FIRST to avoid slug conflicts
Route::get('/product/{product:slug}', ProductDetails::class)->name('product.details');

// Category Routes (consistent prefix)
Route::get('/category/{category:slug}', Categorydetails::class)->name('category.details');
Route::get('/category/{category:slug}/{subcategory:slug}', SubCategoryDetails::class)->name('subcategory.details');

// Static Pages
Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');
Route::view('/terms-and-conditions', 'terms-and-conditions')->name('terms-and-conditions');
Route::view('/refund-policy', 'refund-policy')->name('refund-policy');

// Protected Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/user/orders', UserOrders::class)->name('user.orders');
    Route::get('/user/orders/{order:order_number}', UserOrderDetails::class)->name('user.orders.details');
    Route::get('/user/payments', UserPayments::class)->name('user.payments');
});
