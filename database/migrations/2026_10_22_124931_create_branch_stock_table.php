<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('branch_stock', function (Blueprint $t) {
            $t->id();
            $t->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $t->foreignId('mineral_id')->constrained()->cascadeOnDelete();
            $t->integer('quantity')->default(0);
            $t->integer('min_qty')->default(0); // per-branch override
            $t->timestamps();
            $t->unique(['branch_id', 'mineral_id']);
            $t->index(['branch_id', 'quantity']);
        });
    }
    public function down(): void { Schema::dropIfExists('branch_stock'); }
};