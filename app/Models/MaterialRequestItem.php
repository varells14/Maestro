<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialRequestItem extends Model
{
    use HasFactory;

    protected $table = 'material_request_item';

    protected $fillable = [
        'request_id',
        'product',
        'quantity',
    ];

    public function request()
    {
        return $this->belongsTo(MaterialRequest::class, 'request_id');
    }
}
