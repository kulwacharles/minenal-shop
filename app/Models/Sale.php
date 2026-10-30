<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_no','branch_id', 'customer_id', 'user_id',
        'subtotal', 'discount', 'tax', 'total',
        'payment_method', 'status', 'notes', 'sold_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax'      => 'decimal:2',
        'total'    => 'decimal:2',
        'sold_at'  => 'datetime',
    ];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function user()     { return $this->belongsTo(\App\Models\User::class); }
    public function items()    { return $this->hasMany(SaleItem::class); }
    public function movements(){ return $this->morphMany(StockMovement::class, 'reference'); }
    public function branch() { return $this->belongsTo(\App\Models\Branch::class); }
}