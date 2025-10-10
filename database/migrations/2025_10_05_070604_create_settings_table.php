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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('website_name');
            $table->string('phone');
            $table->string('email');
            $table->string('favicon')->nullable();
            $table->string('logo')->nullable();
            $table->string('f_link')->nullable();
            $table->string('i_link')->nullable();
            $table->string('t_link')->nullable();
            $table->string('y_link')->nullable();
            $table->string('tw_link')->nullable();
            $table->string('lnkd_link')->nullable();
            $table->text('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
