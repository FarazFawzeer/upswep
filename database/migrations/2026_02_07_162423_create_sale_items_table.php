<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('sale_id')
                ->constrained('sales')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnDelete();

            // Snapshot fields (VERY IMPORTANT)
            $table->string('product_name');
            $table->string('barcode_snapshot')->nullable();

            // Quantities & prices
            $table->unsignedInteger('qty');

            $table->decimal('unit_price', 12, 2);
            $table->decimal('unit_cost', 12, 2);

            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2);

            $table->timestamps();

            // Indexes for reports
            $table->index(['sale_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
