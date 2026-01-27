<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'movement_type',   // purchase, sale, adjustment, return
        'qty_change',
        'reference_type',  // stock_entry, sale, etc.
        'reference_id',
        'note',
        'created_by',
    ];

    protected $casts = [
        'qty_change' => 'integer',
        'reference_id' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
