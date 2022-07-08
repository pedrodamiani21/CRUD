<?php

namespace App\Repositories;

use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Models\Customer;


class CustomerRepository implements CustomerRepositoryInterface
{
    protected $entity;
    public function __construct(Customer $customer)
    {
        $this->entity = $customer;
    }

    public function allCustomers($data)
    {
        return $this->entity
        ->where('name','LIKE','%'.$data['q'].'%')
        ->orWhere('email','LIKE','%'.$data['q'].'%')
        ->orWhere('cpf','LIKE','%'.$data['q'].'%')
        ->orWhere('id','LIKE','%'.$data['q'].'%')
        ->paginate(20);
    }


    public function createCustomer($data)
    {
        $customer = $this->entity->create($data);
        return 'Customer registered successfully';
    }

    public function showCustomer($data)
    {
        return $this->entity->where('id', $data['id'])->get();
    }
    
    public function editCustomer($data)
    {   
        $customer = $this->entity->where('id', $data['id'])->update($data);
        return 'Successfully changed data!';
    }

    public function deleteCustomer($data)
    {
        $customer = $this->entity->where('id', $data['id'])->delete();;
        return 'Successful deleting customer!';
    }
}