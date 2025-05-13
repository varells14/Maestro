<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;
    
    protected $fillable = ['reference_number', 'product_id', 'quantity', 'date', 'destination', 'notes'];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}