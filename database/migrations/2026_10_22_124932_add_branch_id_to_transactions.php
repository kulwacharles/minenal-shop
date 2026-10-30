<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        foreach (['sales', 'purchases', 'stock_movements'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->foreignId('branch_id')->nullable()->after('id')
                  ->constrained()->nullOnDelete();
            });
        }
    }
    public function down(): void {
        foreach (['sales', 'purchases', 'stock_movements'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropConstrainedForeignId('branch_id');
            });
        }
    }
};