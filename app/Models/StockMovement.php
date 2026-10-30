<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'mineral_id', 'user_id', 'type', 'quantity', 'branch_id',
        'reference_type', 'reference_id', 'notes',
    ];

    public function mineral()   { return $this->belongsTo(Mineral::class); }
    public function user()      { return $this->belongsTo(\App\Models\User::class); }
    public function reference() { return $this->morphTo(); }
    public function branch() { return $this->belongsTo(\App\Models\Branch::class); }
}