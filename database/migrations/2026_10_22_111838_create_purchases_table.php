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
       Schema::create('purchases', function (Blueprint $t) {
            $t->id();
            $t->string('reference_no')->unique();
            $t->foreignId('supplier_id')->constrained()->restrictOnDelete();
            $t->foreignId('user_id')->constrained();
            $t->decimal('total', 12, 2)->default(0);
            $t->string('status', 20)->default('received'); // pending, received, cancelled
            $t->text('notes')->nullable();
            $t->timestamp('purchased_at')->useCurrent();
            $t->timestamps();
            $t->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
