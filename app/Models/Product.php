<?php

// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = ['code', 'name', 'category_id', 'stock', 'description'];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }
    
    public function stockOuts()
    {
        return $this->hasMany(StockOut::class);
    }
}
