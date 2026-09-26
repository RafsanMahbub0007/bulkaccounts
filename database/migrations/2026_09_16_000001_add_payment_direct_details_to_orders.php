<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'pay_address')) {
                $table->string('pay_address', 255)->nullable()
                    ->after('nowpayments_payment_id');
            }
            if (!Schema::hasColumn('orders', 'pay_amount')) {
                $table->decimal('pay_amount', 18, 8)->nullable()
                    ->after('pay_address');
            }
            if (!Schema::hasColumn('orders', 'pay_currency')) {
                $table->string('pay_currency', 40)->nullable()
                    ->after('pay_amount');
            }
        });

        Schema::table('pre_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_orders', 'pay_address')) {
                $table->string('pay_address', 255)->nullable()
                    ->after('nowpayments_payment_id');
            }
            if (!Schema::hasColumn('pre_orders', 'pay_amount')) {
                $table->decimal('pay_amount', 18, 8)->nullable()
                    ->after('pay_address');
            }
            if (!Schema::hasColumn('pre_orders', 'pay_currency')) {
                $table->string('pay_currency', 40)->nullable()
                    ->after('pay_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            foreach (['pay_currency', 'pay_amount', 'pay_address'] as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('pre_orders', function (Blueprint $table) {
            foreach (['pay_currency', 'pay_amount', 'pay_address'] as $col) {
                if (Schema::hasColumn('pre_orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
