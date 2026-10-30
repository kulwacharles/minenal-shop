<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Mineral extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'supplier_id', 'name', 'sku', 'description', 'image',
        'unit', 'cost_price', 'sale_price', 'stock_qty', 'min_stock_qty', 'is_active',
    ];

    protected $casts = [
        'cost_price'    => 'decimal:2',
        'sale_price'    => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    public function category()       { return $this->belongsTo(Category::class); }
    public function supplier()       { return $this->belongsTo(Supplier::class); }
    public function saleItems()      { return $this->hasMany(SaleItem::class); }
    public function purchaseItems()  { return $this->hasMany(PurchaseItem::class); }
    public function stockMovements() { return $this->hasMany(StockMovement::class); }

    public function scopeLowStock($q) {
        return $q->whereColumn('stock_qty', '<=', 'min_stock_qty');
    }

    public function scopeActive($q) { return $q->where('is_active', true); }

    public function scopeSearch($q, ?string $term) {
        if (!$term) return $q;
        return $q->where(function ($q) use ($term) {
            $q->where('name', 'like', "%$term%")
              ->orWhere('sku', 'like', "%$term%");
        });
    }

    public function getImageUrlAttribute(): string {
        return $this->image
            ? Storage::url($this->image)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=d4a24c&color=fff&size=128';
    }

    public function getIsLowStockAttribute(): bool {
        return $this->stock_qty <= $this->min_stock_qty;
    }
        public function branchStocks() { return $this->hasMany(BranchStock::class); }

    public function stockInBranch(int $branchId): int {
        return (int) ($this->branchStocks->firstWhere('branch_id', $branchId)?->quantity ?? 0);
    }
}