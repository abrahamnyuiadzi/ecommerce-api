<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
       use HasFactory;

    protected $fillable = [
        'total',
        'status',
        'payment_method',
        'phone',
        'address'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
