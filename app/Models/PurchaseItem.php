<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = ['purchase_id', 'mineral_id', 'quantity', 'unit_cost', 'subtotal'];
    protected $casts    = ['unit_cost' => 'decimal:2', 'subtotal' => 'decimal:2'];

    public function purchase() { return $this->belongsTo(Purchase::class); }
    public function mineral()  { return $this->belongsTo(Mineral::class); }
}