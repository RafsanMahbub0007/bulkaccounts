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
        $tables = ['products', 'categories', 'sub_categories', 'posts'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'meta_title')) {
                    $table->string('meta_title')->nullable()->after('slug');
                }
                // Check if description exists, if not add meta_description (using description as meta_description usually)
                // But for explicit control, let's keep using 'description' as meta_description if it exists.
                // However, let's just ensure we have meta_title.
            });
        }
    }

    public function down(): void
    {
        $tables = ['products', 'categories', 'sub_categories', 'posts'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                 if (Schema::hasColumn($table->getTable(), 'meta_title')) {
                    $table->dropColumn('meta_title');
                }
            });
        }
    }
};
