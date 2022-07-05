<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\OrderService;

class OrderController extends Controller
{
    protected $OrderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    public function index()
    {
        return $this->orderService->validateAllOrders();
    }

    public function create(Request $request)
    {
        $customerId = $request->input('customer_id') ?? null;
        $productId = $request->input('product_id') ?? null;
        $orderDate = $request->input('order_date') ?? null;
        $orderAmount = $request->input('order_amount') ?? null;
        $status = $request->input('status') ?? null;

        $data = [
        'customer_id' => $customerId, 
        'product_id' => $productId, 
        'order_date' => $orderDate, 
        'order_amount' => $orderAmount,
        'status' => $status
        ];

        return $this->orderService->validateOrderCreate($data);
    }

    public function show(Request $request)
    {
        $id = $request->input('id') ?? null;
        $data = ['id' => $id];
        return $this->orderService->validateOrderShow($data);
    }

    public function edit(Request $request)
    {
        $id = $request->input('id') ?? null;
        $customerId = $request->input('customer_id') ?? null;
        $productId = $request->input('product_id') ?? null;
        $orderDate = $request->input('order_date') ?? null;
        $orderAmount = $request->input('order_amount') ?? null;
        $status = $request->input('status') ?? null;
        $data = [
            'id' => $id,
            'customer_id' => $customerId, 
            'product_id' => $productId, 
            'order_date' => $orderDate, 
            'order_amount' => $orderAmount,
            'status' => $status
            ];
        $data = array_filter($data);
        return $this->orderService->validateOrderEdit($data);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id') ?? null;
        $data = ['id' => $id];
        return $this->orderService->validateOrderDelete($data);
    }
}
