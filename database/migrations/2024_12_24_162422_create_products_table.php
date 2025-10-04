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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('subcategory_id')->constrained('sub_categories')->cascadeOnDelete();
            $table->json('feature_ids')->nullable();
            $table->float('purchase_price', 10, 2);
            $table->float('selling_price', 10, 2);
            $table->integer('stock')->default(0);
            $table->integer('min_order_qty')->default(10);
            $table->string('product_image')->nullable();
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
