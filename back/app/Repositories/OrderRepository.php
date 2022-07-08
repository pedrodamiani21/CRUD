<?php

namespace App\Repositories;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Models\Order;


class OrderRepository implements OrderRepositoryInterface
{
    protected $entity;
    public function __construct(Order $order)
    {
        $this->entity = $order;
    }

    public function allOrders($data)
    {
        return $this->entity
        ->where('status','LIKE','%'.$data['q'].'%')
        ->orWhere('id','LIKE','%'.$data['q'].'%')
        ->orWhere('customer_id','LIKE','%'.$data['q'].'%')
        ->orWhere('product_id','LIKE','%'.$data['q'].'%')
        ->orWhere('order_amount','LIKE','%'.$data['q'].'%')
        ->orWhere('order_date','LIKE','%'.$data['q'].'%')
        ->paginate(20);
    }

    public function createOrder($data)
    {
        $order = $this->entity->create($data);
        return 'Order registered successfully';
    }

    public function showOrder($data)
    {
        return $this->entity->where('id', $data['id'])->get();
    }
    
    public function editOrder($data)
    {       
        $order = $this->entity->where('id', $data['id'])->update($data);
        return 'Successfully changed data!';
    }

    public function deleteOrder($data)
    {
        $order = $this->entity->where('id', $data['id'])->delete();;
        return 'Successful deleting order!';
    }
}