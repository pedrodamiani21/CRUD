<?php

namespace App\Repositories;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Models\Product;


class ProductRepository implements ProductRepositoryInterface
{
    protected $entity;
    public function __construct(Product $product)
    {
        $this->entity = $product;
    }

    public function allProducts()
    {
        return $this->entity->paginate(20);
    }

    public function createProduct($data)
    {
        $product = $this->entity->create($data);
        return 'Product registered successfully';
    }

    public function showProduct($data)
    {
        return $this->entity->where('id', $data['id'])->get();
    }
    
    public function editProduct($data)
    {   
        $product = $this->entity->where('id', $data['id'])->update($data);
        return 'Successfully changed data!';
    }

    public function deleteProduct($data)
    {
        $product = $this->entity->where('id', $data['id'])->delete();;
        return 'Successful deleting product!';
    }
}