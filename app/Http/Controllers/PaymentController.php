<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\ProductAccount;
use App\Models\Payment;
use Google\Client;
use Google\Service\Sheets;

class PaymentController extends Controller
{
    private function sheets(): Sheets
    {
        $client = new Client();
        $client->setApplicationName('Order Fulfillment');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(config('services.google.credentials'));

        return new Sheets($client);
    }

    public function handle(Request $request)
    {
        $data = $request->all();

        if (!isset($data['order_id'], $data['payment_status'])) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $order = Order::where('order_number', $data['order_id'])->first();
        if (!$order) return response()->json(['status' => 'order_not_found']);

        if ($order->payment_status === 'paid') {
            return response()->json(['status' => 'already_processed']);
        }

        if ($data['payment_status'] === 'finished') {
            $this->completeOrder($order, $data);
        }

        return response()->json(['status' => 'ok']);
    }

    private function completeOrder(Order $order, array $payload): void
    {
        DB::transaction(function () use ($order, $payload) {
            $service = $this->sheets();

            Log::info("Processing order ID: {$order->id}", $payload);

            // Update order first
            $order->update([
                'payment_status' => 'paid',
                'order_status'   => 'completed',
                'completed_at'   => now(),
                'transaction_reference' => $payload['payment_id'] ?? null,
            ]);

            foreach ($order->orderItems as $item) {
                if (!$item->product) continue;

                $product = $item->product;
                $limit = (int)$item->quantity;

                // Fetch unsold accounts
                $accounts = ProductAccount::where('product_id', $product->id)
                    ->where('status', 'unsold')
                    ->lockForUpdate()
                    ->limit($limit)
                    ->get();

                if ($accounts->count() < $limit) {
                    throw new \Exception("Insufficient stock for product {$product->name}");
                }

                // Mark accounts as sold
                foreach ($accounts as $acc) {
                    $acc->update(['status' => 'sold']);
                    Log::info("Account ID {$acc->id} marked as sold");
                }

                // Decrement stock
                $product->decrement('stock', $limit);
                Log::info("Product ID {$product->id} stock decremented by {$limit}, new stock: {$product->stock}");

                // Update Google Sheet if applicable
                if ($product->google_sheet_id && $accounts->isNotEmpty()) {
                    $order->updateGoogleSheet($product->google_sheet_id, $accounts);
                    Log::info("Google Sheet updated for product ID {$product->id}");
                }
            }

            // Record the payment
            Payment::updateOrCreate(
                ['transaction_id' => $payload['payment_id'] ?? null],
                [
                    'order_id' => $order->id,
                    'amount' => $payload['price_amount'] ?? 0,
                    'currency' => $payload['price_currency'] ?? 'USD',
                    'status' => 'completed',
                    'paid_at' => now(),
                ]
            );

            Log::info("Order ID {$order->id} completed successfully");
        });
    }
}
