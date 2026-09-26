<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
        });

        Schema::table('pre_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_orders', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });

        Schema::table('pre_orders', function (Blueprint $table) {
            if (Schema::hasColumn('pre_orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};
