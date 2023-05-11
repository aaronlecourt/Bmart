<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address', 'city', 'country', 'postal_code', 'phone_number', 'status', 'quantity', 'price'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

