<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category_name'];

    /**
     * Get the category's vendors.
     */
    public function vendors()
    {
        return $this->belongsToMany(User::class, 'category_vendor')->withPivot('deleted');
    }

    /**
     * Get the category's products.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}