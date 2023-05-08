<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'address', 'city', 'country', 'postalcode', 'phone', 'email', 'total_price', 'status'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')->withPivot('quantity', 'price');
    }
}
