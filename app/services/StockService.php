<?php
namespace App\Services;

use App\Models\{Mineral, BranchStock, StockMovement};
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StockService
{
    /** Decrease stock at a specific branch. */
    public function decrease(Mineral $mineral, int $qty, int $branchId, ?string $notes = null, $reference = null): StockMovement
    {
        return DB::transaction(function () use ($mineral, $qty, $branchId, $notes, $reference) {
            $row = $this->lockedRow($mineral->id, $branchId);
            if ($row->quantity < $qty) {
                throw new RuntimeException(
                    "Insufficient stock for {$mineral->name} at this branch (need {$qty}, have {$row->quantity})."
                );
            }
            $row->decrement('quantity', $qty);
            $mineral->decrement('stock_qty', $qty);

            return $this->log($mineral, $branchId, -$qty, 'sale', $notes, $reference);
        });
    }

    /** Increase stock at a branch. */
    public function increase(Mineral $mineral, int $qty, int $branchId, string $type = 'purchase', ?string $notes = null, $reference = null): StockMovement
    {
        return DB::transaction(function () use ($mineral, $qty, $branchId, $type, $notes, $reference) {
            $row = $this->lockedRow($mineral->id, $branchId);
            $row->increment('quantity', $qty);
            $mineral->increment('stock_qty', $qty);

            return $this->log($mineral, $branchId, $qty, $type, $notes, $reference);
        });
    }

    /** Manual adjustment (can be +/-) at a branch. */
    public function adjust(Mineral $mineral, int $delta, int $branchId, ?string $notes = null): StockMovement
    {
        return DB::transaction(function () use ($mineral, $delta, $branchId, $notes) {
            $row = $this->lockedRow($mineral->id, $branchId);
            $newQty = $row->quantity + $delta;
            if ($newQty < 0) {
                throw new RuntimeException("Adjustment would make stock negative for {$mineral->name} at this branch.");
            }
            $row->update(['quantity' => $newQty]);
            $mineral->update(['stock_qty' => max(0, $mineral->stock_qty + $delta)]);

            return $this->log($mineral, $branchId, $delta, 'adjustment', $notes);
        });
    }

    protected function lockedRow(int $mineralId, int $branchId): BranchStock
    {
        return BranchStock::lockForUpdate()->firstOrCreate(
            ['mineral_id' => $mineralId, 'branch_id' => $branchId],
            ['quantity' => 0, 'min_qty' => 0]
        );
    }

    protected function log(Mineral $m, int $branchId, int $qty, string $type, ?string $notes, $reference = null): StockMovement
    {
        return StockMovement::create([
            'mineral_id'     => $m->id,
            'branch_id'      => $branchId,
            'user_id'        => auth()->id(),
            'type'           => $type,
            'quantity'       => $qty,
            'notes'          => $notes,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id'   => $reference?->id,
        ]);
    }
}