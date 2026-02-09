<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_items', 'product_name')) {
                $table->string('product_name')->after('product_id');
            }

            if (!Schema::hasColumn('sale_items', 'barcode_snapshot')) {
                $table->string('barcode_snapshot', 100)->nullable()->after('product_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            if (Schema::hasColumn('sale_items', 'product_name')) {
                $table->dropColumn('product_name');
            }

            if (Schema::hasColumn('sale_items', 'barcode_snapshot')) {
                $table->dropColumn('barcode_snapshot');
            }
        });
    }
};
