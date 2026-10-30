<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = ['sale_id', 'mineral_id', 'quantity', 'unit_price', 'subtotal'];
    protected $casts    = ['unit_price' => 'decimal:2', 'subtotal' => 'decimal:2'];

    public function sale()    { return $this->belongsTo(Sale::class); }
    public function mineral() { return $this->belongsTo(Mineral::class); }
}