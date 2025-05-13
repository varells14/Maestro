<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_order';

    protected $fillable = [
        'project',
        'purchase_number',
        'purchase_name',
        'priority',
        'purchase_date',
        'notes',
        'status',
        'checker',
        'checker_at',
        'approved',
        'approved_at',
        'checker_rejected_notes',
        'approved_rejected_notes',
    ];

    protected $dates = [
        'purchase_date',
        'checker_at',
        'approved_at',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_id');
    }
}
