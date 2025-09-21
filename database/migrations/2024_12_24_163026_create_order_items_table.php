<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');  // Foreign key to the orders table
            $table->foreignId('product_id')->constrained()->onDelete('cascade');  // Foreign key to the products table (Gmail, LinkedIn, etc.)
            $table->integer('quantity');  // Number of accounts purchased for this product
            $table->decimal('unit_price', 10, 2);  // Price per unit of the product/account
            $table->decimal('total_price', 10, 2);  // Total price for this order item (unit_price * quantity)
            $table->timestamps();  // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
