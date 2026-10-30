<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'reference_no', 'supplier_id', 'user_id', 'branch_id', 'total', 'status', 'notes', 'purchased_at',
    ];
    protected $casts = [
        'total' => 'decimal:2', 'purchased_at' => 'datetime',
    ];

    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function user()     { return $this->belongsTo(\App\Models\User::class); }
    public function items()    { return $this->hasMany(PurchaseItem::class); }
    public function movements(){ return $this->morphMany(StockMovement::class, 'reference'); }
    public function branch() { return $this->belongsTo(\App\Models\Branch::class); }
}