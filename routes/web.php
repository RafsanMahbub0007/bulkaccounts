<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Livewire Pages
use App\Livewire\Home;
use App\Livewire\Products;
use App\Livewire\Cart;
use App\Livewire\Checkout;
use App\Livewire\Contact;
use App\Livewire\AboutDetails;
use App\Livewire\Categorydetails;
use App\Livewire\SubCategoryDetails;
use App\Livewire\ProductDetails;
use App\Livewire\UserOrders;
use App\Livewire\UserOrderDetails;
use App\Livewire\UserPayments;
use App\Livewire\Posts;
use App\Livewire\PostDetails;
use App\Livewire\FaqDetails;
use App\Livewire\Guidelinedetails;
use App\Livewire\PrivacyPolicy;
use App\Livewire\Terms;

// Controllers
use App\Http\Controllers\CheckoutController;
use App\Models\Order;
use App\Models\OrderItem;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', Home::class)->name('home');
Route::get('/pricing', Products::class)->name('pricing');

Route::get('/about', AboutDetails::class)->name('about');
Route::get('/faq', FaqDetails::class)->name('faq');
Route::get('/guidlines', Guidelinedetails::class)->name('guidlines');

Route::get('/blog', Posts::class)->name('blog');
Route::get('/posts/{post:slug}', PostDetails::class)->name('post.show');

Route::get('/terms', Terms::class)->name('terms');
Route::get('/privacy-policy', PrivacyPolicy::class)->name('privacy');

Route::get('/cart', Cart::class)->name('cart');

// Checkout page (input)
Route::get('/checkout', Checkout::class)->name('checkout');

Route::get('/contact', Contact::class)->name('contact');

// Product details
Route::get('/product/{product:slug}', ProductDetails::class)->name('product.details');


/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Order history
    Route::get('/user/orders', UserOrders::class)->name('user.orders');
    Route::get('/user/orders/{order:order_number}', UserOrderDetails::class)->name('user.orders.details');

    // User payment logs
    Route::get('/user/payments', UserPayments::class)->name('user.payments');

    // Secure checkout processing
    Route::get('/checkout/process', [CheckoutController::class, 'process'])
        ->name('checkout.process');

    // Protected download route
    Route::get('/download/{order}', function (Order $order) {

        // Only the order owner can download
        abort_unless($order->user_id === auth()->id(), 403);

        // Check file exists
        if (!$order->download_file || !Storage::exists($order->download_file)) {
            abort(404);
        }

        // Serve securely with a friendly name
        $fileName = 'accounts_' . $order->order_number . '.xlsx';
        return Storage::download($order->download_file, $fileName);
    })->name('order.download');
});


/*
|--------------------------------------------------------------------------
| Payment Callback Routes (Safe)
|--------------------------------------------------------------------------
*/

Route::get('/payment/success/{order}', function (Order $order) {
    return view('payment.success', compact('order'));
})->name('payment.success');

Route::get('/payment/cancel/{order}', function (Order $order) {
    return view('payment.cancel', compact('order'));
})->name('payment.cancel');



/*
|--------------------------------------------------------------------------
| Dynamic Category Routes — MUST BE LAST
|--------------------------------------------------------------------------
*/

Route::get('/{category:slug}/{subcategory:slug}', SubCategoryDetails::class)
    ->name('subcategory.details');

Route::get('/{category:slug}', Categorydetails::class)
    ->name('category.details');
