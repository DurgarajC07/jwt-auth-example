<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'email',
        'phone_number',
        'address_line_1',
        'city',
        'country',
        'postcode',
        'loyalty_card',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
