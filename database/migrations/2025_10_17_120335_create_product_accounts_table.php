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
            $table->string('email');
            $table->text('password_encrypted')->nullable();   // encrypted, reversible
            $table->text('two_fa_secret_encrypted')->nullable(); // encrypted
            $table->json('meta')->nullable(); // extra fields (note, country, notes...)

            $table->unique(['product_id', 'email']);

            $table->timestamps();
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
