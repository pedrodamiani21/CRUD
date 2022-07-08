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

    public function validateAllCustomers($data) 
    {
        return response([$this->customerRepository->allCustomers($data)], 200);
    }

    public function validateCustomerCreate($data)
    {
        $validator = Validator::make($data, [
            'cpf' => 'required|min:11|max:11|unique:customers',
            'email' => 'required|email|unique:customers',
            'name' => 'required'
        ]);

        if(!$validator->fails())
        {
            return response([$this->customerRepository->createCustomer($data)], 200);
        }

        return response([$validator->messages()], 400);
    }

    public function validateCustomerShow($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|numeric',
        ]);

        if(!$validator->fails())
        {
            return response([$this->customerRepository->showCustomer($data)], 200);
        }

        return response([$validator->messages()], 400);
    }

    public function validateCustomerEdit($data)
    {  
        if(count($data) >= 2) 
        {
            $validator = Validator::make($data, [
            'id' => 'required|numeric',
            'cpf' => 'min:11|max:11|numeric',
            'email' => 'email',
            ]);
        
            if(!$validator->fails())
            {
                return response([$this->customerRepository->editCustomer($data)], 200);
            }    
        
            return response([$validator->messages()], 400);
        }

        return response(['You have not filled in any data to change!'], 400);
    }

    public function validateCustomerDelete($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|numeric',
        ]);

        if(!$validator->fails())
        {
            return response([$this->customerRepository->deleteCustomer($data)], 200);
        }

        return response([$validator->messages()], 400);
    }
}