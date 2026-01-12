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
        Schema::create('product_accounts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->cascadeOnDelete();
        $table->string('email')->collation('utf8mb4_unicode_ci');
        $table->enum('status', ['unsold', 'sold', 'banned'])->default('unsold');
        $table->json('meta')->nullable();
        $table->json('meta_headers')->nullable();
        $table->timestamps();

        $table->unique(['product_id', 'email']);
        $table->index(['product_id', 'status']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_accounts');
    }
};
