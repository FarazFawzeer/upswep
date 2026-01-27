<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'address',
        'phone',
        'logo',
        'currency',
        'default_tax_percent',
    ];

    protected $casts = [
        'default_tax_percent' => 'decimal:2',
    ];
}
