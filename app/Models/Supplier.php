<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'contact_name', 'email', 'phone', 'address', 'notes'];
    public function minerals()  { return $this->hasMany(Mineral::class); }
    public function purchases() { return $this->hasMany(Purchase::class); }
}