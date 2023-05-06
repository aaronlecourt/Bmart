<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart_userid', 'cart_productid', 
        'cart_categoryid', 'cart_quantity', 
        'cart_price'];
    
}
