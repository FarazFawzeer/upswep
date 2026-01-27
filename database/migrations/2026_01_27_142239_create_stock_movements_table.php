<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('stock_movements', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->cascadeOnDelete();

        $table->string('movement_type'); // purchase, sale, adjustment, return
        $table->integer('qty_change');    // + or -
        $table->string('reference_type')->nullable();
        $table->unsignedBigInteger('reference_id')->nullable();
        $table->text('note')->nullable();

        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();

        $table->index(['movement_type', 'product_id']);
    });
}

public function down(): void
{
    Schema::dropIfExists('stock_movements');
}

};
