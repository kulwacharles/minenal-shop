<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'code', 'address', 'phone', 'email', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function users()         { return $this->hasMany(User::class); }
    public function stock()         { return $this->hasMany(BranchStock::class); }
    public function minerals()      { return $this->belongsToMany(Mineral::class, 'branch_stock')
                                          ->withPivot('quantity', 'min_qty')->withTimestamps(); }
    public function sales()         { return $this->hasMany(Sale::class); }
    public function purchases()     { return $this->hasMany(Purchase::class); }
    public function stockMovements(){ return $this->hasMany(StockMovement::class); }

    public function scopeActive($q) { return $q->where('is_active', true); }
}