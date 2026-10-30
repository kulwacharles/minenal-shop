<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        $mainBranchId = DB::table('branches')->where('code', 'MAIN')->value('id');
        if (!$mainBranchId) return;

        foreach (DB::table('minerals')->get() as $m) {
            DB::table('branch_stock')->insert([
                'branch_id'  => $mainBranchId,
                'mineral_id' => $m->id,
                'quantity'   => $m->stock_qty ?? 0,
                'min_qty'    => $m->min_stock_qty ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Attach existing sales/purchases/movements to main branch
        DB::table('sales')->whereNull('branch_id')->update(['branch_id' => $mainBranchId]);
        DB::table('purchases')->whereNull('branch_id')->update(['branch_id' => $mainBranchId]);
        DB::table('stock_movements')->whereNull('branch_id')->update(['branch_id' => $mainBranchId]);
    }

    public function down(): void {}
};