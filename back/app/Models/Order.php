<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'id',
        'customer_id',
        'product_id',
        'order_date',
        'order_amount',
        'status'
    ];
}
