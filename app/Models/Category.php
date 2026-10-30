<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'color'];

    protected static function booted(): void {
        static::saving(function (Category $c) {
            if (empty($c->slug)) $c->slug = Str::slug($c->name);
        });
    }

    public function minerals() { return $this->hasMany(Mineral::class); }

    public function getRouteKeyName(): string { return 'slug'; }
}