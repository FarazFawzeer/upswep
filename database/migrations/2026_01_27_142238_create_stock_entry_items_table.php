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
    Schema::create('stock_entries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
        $table->string('entry_no')->unique();
        $table->date('entry_date');
        $table->text('notes')->nullable();
        $table->foreignId('created_by')->constrained('users');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('stock_entries');
}

};
