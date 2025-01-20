<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'products';
    protected $fillable = ['name', 'price', 'description', 'image', 'stock', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
    
    public function listOrders()
    {
        return $this->belongsToMany(User::class, 'orders', 'product_id', 'user_id');
    }
}
