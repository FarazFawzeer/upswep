<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEntryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_entry_id',
        'product_id',
        'qty',
        'unit_cost',
        'line_total',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function stockEntry()
    {
        return $this->belongsTo(StockEntry::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
