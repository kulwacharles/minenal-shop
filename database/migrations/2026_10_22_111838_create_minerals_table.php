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
        Schema::create('minerals', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained()->cascadeOnDelete();
            $t->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name');
            $t->string('sku')->unique();
            $t->text('description')->nullable();
            $t->string('image')->nullable();
            $t->string('unit', 20)->default('piece'); // piece, gram, carat, kg
            $t->decimal('cost_price', 10, 2)->default(0);
            $t->decimal('sale_price', 10, 2)->default(0);
            $t->integer('stock_qty')->default(0);
            $t->integer('min_stock_qty')->default(5);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->softDeletes();
            $t->index(['stock_qty', 'min_stock_qty']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minerals');
    }
};
