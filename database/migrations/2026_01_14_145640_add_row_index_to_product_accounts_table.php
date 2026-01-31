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
        if (!Schema::hasColumn('product_accounts', 'row_index')) {
            Schema::table('product_accounts', function (Blueprint $table) {
                $table->integer('row_index')->default(0)->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('product_accounts', 'row_index')) {
            Schema::table('product_accounts', function (Blueprint $table) {
                $table->dropColumn('row_index');
            });
        }
    }
};
