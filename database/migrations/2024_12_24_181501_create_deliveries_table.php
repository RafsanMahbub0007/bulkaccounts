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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade'); // Foreign key to the order_items table
            $table->enum('status', ['pending', 'delivered'])->default('pending'); // Delivery status
            $table->timestamp('delivered_at')->nullable(); // Timestamp when the delivery was completed
            $table->text('accounts')->nullable(); // accounts information or file links
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
