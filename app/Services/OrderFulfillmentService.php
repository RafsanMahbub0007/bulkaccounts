<?php

namespace App\Services;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\ProductAccount;
use App\Models\Payment;
use App\Exports\OrderAccountsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderFulfilledMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

use Google\Client;
use Google\Service\Sheets;

class OrderFulfillmentService
{
    private function formatDeliveredAccounts($accounts): string
    {
        return $accounts
            ->map(function ($account) {
                $metaValues = collect($account->meta ?? [])
                    ->filter(fn ($value) => filled($value))
                    ->implode(' | ');

                return trim($account->email . ($metaValues ? ' | ' . $metaValues : ''));
            })
            ->filter()
            ->implode(PHP_EOL);
    }

    private function sheets(): Sheets
    {
        $client = new Client();
        $client->setApplicationName('Order Fulfillment');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(config('services.google.credentials'));

        return new Sheets($client);
    }

    public function fulfillOrder(Order $order, ?array $paymentPayload = null): void
    {
        if ($order->order_status === 'completed' || $order->payment_status === 'paid') {
            Log::warning("Order {$order->id} is already completed or paid. Skipping fulfillment.");
            return;
        }

        DB::transaction(function () use ($order, $paymentPayload) {
            $service = $this->sheets();
            
            Log::info("Starting fulfillment for order ID: {$order->id}");

            // Update order status
            $orderUpdate = [
                'payment_status' => 'paid',
                'order_status'   => 'completed',
                'completed_at'   => now(),
            ];

            if (Schema::hasColumn('orders', 'transaction_reference')) {
                $orderUpdate['transaction_reference'] = $paymentPayload['payment_id'] ?? null;
            }

            if (Schema::hasColumn('orders', 'nowpayments_payment_id') && ! empty($paymentPayload['payment_id'] ?? null)) {
                $orderUpdate['nowpayments_payment_id'] = $paymentPayload['payment_id'];
            }

            $order->update($orderUpdate);
            
            $allSoldAccounts = collect();

            $order->loadMissing('orderItems.product');

            foreach ($order->orderItems as $item) {
                if (!$item->product) continue;

                $product = $item->product;
                $limit = (int)$item->quantity;

                // Fetch unsold accounts
                $accounts = ProductAccount::where('product_id', $product->id)
                    ->where('status', 'unsold')
                    ->orderBy('row_index', 'asc')
                    ->lockForUpdate()
                    ->limit($limit)
                    ->get();

                if ($accounts->count() < $limit) {
                    throw new \Exception("Insufficient stock for product {$product->name}");
                }

                // Mark accounts as sold
                foreach ($accounts as $acc) {
                    $acc->update(['status' => 'sold']);
                    $allSoldAccounts->push($acc);
                    Log::info("Account ID {$acc->id} marked as sold");
                }

                $updatedStock = ProductAccount::where('product_id', $product->id)
                    ->where('status', 'unsold')
                    ->count();

                $product->update(['stock' => $updatedStock]);
                Log::info("Product ID {$product->id} stock updated after fulfillment, new stock: {$updatedStock}");

                Delivery::updateOrCreate(
                    ['order_item_id' => $item->id],
                    [
                        'status' => 'delivered',
                        'delivered_at' => now(),
                        'accounts' => $this->formatDeliveredAccounts($accounts),
                    ]
                );

                // Update Google Sheet if applicable
                if ($product->google_sheet_id && $accounts->isNotEmpty()) {
                    try {
                        $order->updateGoogleSheet($product->google_sheet_id, $accounts);
                        Log::info("Google Sheet updated for product ID {$product->id}");
                    } catch (\Exception $e) {
                        Log::error("Failed to update Google Sheet for product {$product->id}: " . $e->getMessage());
                    }
                }
            }

            // Generate Excel File
            if ($allSoldAccounts->isNotEmpty()) {
                try {
                    $fileName = 'order_' . $order->order_number . '.xlsx';
                    $filePath = 'order-files/' . $fileName;
                    Excel::store(new OrderAccountsExport($allSoldAccounts), $filePath, 'public');
                    $order->update(['download_file' => $filePath]);
                    Log::info("Order file generated: {$filePath}");

                    // Send Email to Guest or User
                    $recipientEmail = $order->guest_email ?? $order->user?->email;
                    
                    if ($recipientEmail) {
                        try {
                            Mail::to($recipientEmail)->send(new OrderFulfilledMail($order, $filePath));
                            Log::info("Order fulfillment email sent to: {$recipientEmail}");
                        } catch (\Exception $e) {
                            Log::error("Failed to send order email: " . $e->getMessage());
                        }
                    } else {
                        Log::warning("No recipient email found for Order ID {$order->id}. Skipping email.");
                    }

                } catch (\Exception $e) {
                    Log::error("Failed to generate order file: " . $e->getMessage());
                }
            }

            // Record the payment if payload is provided
            if ($paymentPayload) {
                Payment::updateOrCreate(
                    ['transaction_id' => $paymentPayload['payment_id'] ?? null],
                    [
                        'order_id' => $order->id,
                        'amount' => $paymentPayload['price_amount'] ?? 0,
                        'currency' => $paymentPayload['price_currency'] ?? 'USD',
                        'status' => 'completed',
                        'paid_at' => now(),
                    ]
                );
            }

            Log::info("Order ID {$order->id} fulfilled successfully");
        });
    }
}
