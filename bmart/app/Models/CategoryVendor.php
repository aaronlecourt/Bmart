<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryVendor extends Model
{
    use HasFactory;

    protected $table = 'category_vendor';
    protected $fillable = ['category_id', 'user_id', 'deleted'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}