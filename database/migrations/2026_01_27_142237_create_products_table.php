<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();

        $table->string('name');
        $table->string('brand')->nullable();
        $table->string('size')->nullable();
        $table->string('color')->nullable();

        $table->decimal('cost_price', 12, 2)->default(0);
        $table->decimal('selling_price', 12, 2)->default(0);

        $table->integer('stock_qty')->default(0);
        $table->integer('low_stock_alert_qty')->default(5);

        $table->string('barcode')->unique();
        $table->string('image')->nullable();

        $table->boolean('status')->default(true);
        $table->timestamps();

        $table->index(['name', 'barcode']);
    });
}

public function down(): void
{
    Schema::dropIfExists('products');
}

};
