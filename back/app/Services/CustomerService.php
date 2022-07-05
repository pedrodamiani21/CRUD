<?php
namespace App\Services;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Http\Request;


class CustomerService{

    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function validateCustomerCreate($data)
    {
        $validator = Validator::make($data, [
            'cpf' => 'required|min:11|max:11|unique:customers',
            'email' => 'required|email|unique:customers',
            'name' => 'required'
        ]);

        if(!$validator->fails()){
            return response()->json([$this->customerRepository->createCustomer($data), 200]);
        }
        return response()->json([$validator->messages(), 400]);
    }

    public function validateCustomerShow($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required',
        ]);

        if(!$validator->fails()){
            return response()->json([$this->customerRepository->showCustomer($data), 200]);
        }
        return response()->json([$validator->messages(), 400]);
    }

    public function validateCustomerEdit($data)
    {  
        $validator = Validator::make($data, [
            'id' => 'required',
            'cpf' => 'min:11|max:11|unique:customers',
            'email' => 'email|unique:customers',
        ]);
        
        if(!$validator->fails()){
            return response()->json([$this->customerRepository->editCustomer($data), 200]);
        }
        return response()->json([$validator->messages(), 400]);
    }
    public function validateCustomerDelete($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required',
        ]);

        if(!$validator->fails()){
            return response()->json([$this->customerRepository->deleteCustomer($data), 200]);
        }
        return response()->json([$validator->messages(), 400]);
    }


    



}