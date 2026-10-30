<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchStock extends Model
{
    protected $table    = 'branch_stock';
    protected $fillable = ['branch_id', 'mineral_id', 'quantity', 'min_qty'];
    protected $casts    = ['quantity' => 'integer', 'min_qty' => 'integer'];

    public function branch()  { return $this->belongsTo(Branch::class); }
    public function mineral() { return $this->belongsTo(Mineral::class); }

    public function getIsLowAttribute(): bool {
        return $this->quantity <= $this->min_qty;
    }
}