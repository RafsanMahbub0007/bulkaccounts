<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'nowpayments_invoice_id')) {
                $table->string('nowpayments_invoice_id')->nullable()->after('download_file');
            }

            if (! Schema::hasColumn('orders', 'nowpayments_payment_id')) {
                $table->string('nowpayments_payment_id')->nullable()->after('nowpayments_invoice_id');
            }

            if (! Schema::hasColumn('orders', 'transaction_reference')) {
                $table->string('transaction_reference')->nullable()->after('nowpayments_payment_id');
            }
        });

        Schema::table('pre_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('pre_orders', 'nowpayments_invoice_id')) {
                $table->string('nowpayments_invoice_id')->nullable()->after('download_file');
            }

            if (! Schema::hasColumn('pre_orders', 'nowpayments_payment_id')) {
                $table->string('nowpayments_payment_id')->nullable()->after('nowpayments_invoice_id');
            }

            if (! Schema::hasColumn('pre_orders', 'transaction_reference')) {
                $table->string('transaction_reference')->nullable()->after('nowpayments_payment_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            foreach (['nowpayments_invoice_id', 'nowpayments_payment_id', 'transaction_reference'] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('pre_orders', function (Blueprint $table) {
            foreach (['nowpayments_invoice_id', 'nowpayments_payment_id', 'transaction_reference'] as $column) {
                if (Schema::hasColumn('pre_orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

