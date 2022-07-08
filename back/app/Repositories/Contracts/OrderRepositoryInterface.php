<?php

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface
{

    public function allOrders(array $data);
    public function createOrder(array $data);
    public function editOrder(array $data);
    public function showOrder(array $data);
    public function deleteOrder(array $data);
}