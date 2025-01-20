<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory, HasUuids;
    protected $table = "orders";
    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'address',
        'total_price',
        'quantity',
        'status',
        'user_id',
        'product_id',
    ];
}
