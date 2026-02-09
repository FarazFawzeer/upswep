<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'barcode_snapshot',
        'qty',
        'unit_price',
        'unit_cost',
        'discount_amount',
        'line_total',
    ];


    protected $casts = [
        'unit_price' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
