<?php

namespace App\Repositories\Contracts;

interface CustomerRepositoryInterface
{

    public function allCustomers(array $data);
    public function createCustomer(array $data);
    public function editCustomer(array $data);
    public function showCustomer(array $data);
    public function deleteCustomer(array $data);
}