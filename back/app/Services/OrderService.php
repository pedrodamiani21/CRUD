<?php
namespace App\Services;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\Request;


class OrderService{

    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function validateAllOrders($data) 
    {
        return response([$this->orderRepository->allOrders($data)], 200);
    }

    public function validateOrderCreate($data)
    {
        $validator = Validator::make($data, [
            'customer_id' => 'required|numeric', 
            'product_id' => 'required|numeric',
            'order_date' => 'required|date', 
            'order_amount' => 'required|numeric',
            'status' => 'required'
        ]);

        if(!$validator->fails())
        {
            return response([$this->orderRepository->createOrder($data)], 200);
        }

        return response([$validator->messages()], 400);
    }

    public function validateOrderShow($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|numeric',
        ]);

        if(!$validator->fails())
        {
            return response([$this->orderRepository->showOrder($data)], 200);
        }
        return response([$validator->messages()], 400);
    }

    public function validateOrderEdit($data)
    {  
        
        if(count($data) >= 2) 
        {
            $validator = Validator::make($data, [
                'id' => 'required|numeric',
                'customer_id' => 'numeric', 
                'product_id' => 'numeric',
                'order_date' => 'date', 
                'order_amount' => 'numeric'
        ]);
        
            if(!$validator->fails())
            {
            return response([$this->orderRepository->editOrder($data)], 200);
            }

            return response([$validator->messages()], 400);
        }
        
        return response(['You have not filled in any data to change!'], 400);
    }
    

    public function validateOrderDelete($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|numeric',
        ]);

        if(!$validator->fails())
        {
            return response([$this->orderRepository->deleteOrder($data)], 200);
        }
        
        return response([$validator->messages()], 400);
    }
}