<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
use App\Livewire\PaymentStatus;
use App\Livewire\PrivacyPolicy;
use App\Livewire\Search;
use App\Livewire\Terms;
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
Route::get('/search', Search::class)->name('search');

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

    Route::get('/payment/status/{orderId}', PaymentStatus::class)->name('payment.status');
});

// Public Download Route (Accessible by Guests)
Route::get('/download/order/{order:order_number}', function (Order $order) {
    if (!$order->download_file) {
        abort(404, 'File not generated.');
    }

    // Check public disk
    if (Storage::disk('public')->exists($order->download_file)) {
        return Storage::disk('public')->download(
            $order->download_file,
            'order_' . $order->order_number . '.xlsx'
        );
    }

    // Fallback to local/default disk
    if (Storage::exists($order->download_file)) {
        return Storage::download(
            $order->download_file,
            'order_' . $order->order_number . '.xlsx'
        );
    }

    abort(404, 'File not found on server.');
})->name('order.download');

Route::post('/payment/callback', [PaymentController::class, 'handle'])->name('payment.callback');


use Google\Client;
use Google\Service\Sheets;

Route::get('/test-google-sheet', function () {
    $client = new Client();
    $client->setApplicationName('Test Google Sheets');
    $client->setScopes([Sheets::SPREADSHEETS_READONLY]);
    $client->setAuthConfig(config('services.google.credentials'));

    $service = new Sheets($client);

    // 🔁 Replace with your actual Google Sheet ID
    $spreadsheetId = '1qxaTApGek07QlRsgDx2-DCOqU85wjunbWM4RaZ6sZtE';

    $response = $service->spreadsheets_values->get($spreadsheetId, 'Sheet1!A1:C5');

    return $response->getValues();
});


/*
|--------------------------------------------------------------------------
| Dynamic Category Routes — MUST BE LAST
|--------------------------------------------------------------------------
*/

Route::get('/{category:slug}/{subcategory:slug}', SubCategoryDetails::class)
    ->name('subcategory.details');

Route::get('/{category:slug}', Categorydetails::class)
    ->name('category.details');
