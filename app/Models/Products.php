<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Products extends Model
{
    protected $table = 'products';
    protected $fillable = ['barcode', 'name', 'price', 'stock', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
