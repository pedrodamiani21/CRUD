<?php

namespace App\Models;
use \Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use  HasFactory;
    public $timestamps = false;
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
