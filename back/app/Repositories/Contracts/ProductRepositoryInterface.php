<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface
{

    public function allProducts(array $data);
    public function createProduct(array $data);
    public function editProduct(array $data);
    public function showProduct(array $data);
    public function deleteProduct(array $data);
}