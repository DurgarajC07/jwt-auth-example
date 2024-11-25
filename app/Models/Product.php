<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'coffee_type',
        'roast_type',
        'size',
        'unit_price',
        'price_per_100g',
        'profit',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id');
    }
}
