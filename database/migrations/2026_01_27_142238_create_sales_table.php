<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void
{
    Schema::create('sales', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_no')->unique();
        $table->dateTime('sale_date');

        $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();

        $table->decimal('sub_total', 12, 2);
        $table->decimal('discount_total', 12, 2)->default(0);
        $table->decimal('tax_total', 12, 2)->default(0);
        $table->decimal('grand_total', 12, 2);

        $table->string('payment_method')->nullable();
        $table->foreignId('created_by')->constrained('users');

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('sales');
}

};
