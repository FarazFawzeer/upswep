<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'tax_percent')) {
                $table->decimal('tax_percent', 5, 2)->default(0)->after('tax_total');
            }

            if (!Schema::hasColumn('sales', 'status')) {
                $table->string('status', 20)->default('completed')->after('payment_method');
                // values can be: completed, refunded, void
            }

            // If you want timestamps in sales (only if not already)
            // if (!Schema::hasColumn('sales', 'created_at')) {
            //     $table->timestamps();
            // }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'tax_percent')) {
                $table->dropColumn('tax_percent');
            }

            if (Schema::hasColumn('sales', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
