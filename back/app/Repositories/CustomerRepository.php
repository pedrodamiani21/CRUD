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

    public function index()
    {
        return $this->entity->get();
    }

    public function createCustomer($data)
    {
        $customer = $this->entity->create($data);
        return 'Customer registered successfully';
    }

    public function showCustomer($data)
    {
        return $this->entity->get()->where('id', $data['id']);
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