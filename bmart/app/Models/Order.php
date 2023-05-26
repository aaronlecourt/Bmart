<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email','address', 'city', 'country', 'postal_code', 'phone_number'];
    // protected $vendors = [];
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalPriceAttribute()
    {
        return $this->items->sum(function (OrderItem $item) {
            return $item->quantity * $item->product_price;
        });
    }
}
