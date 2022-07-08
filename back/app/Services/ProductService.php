<?php
namespace App\Services;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;


class ProductService{

    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function validateAllProducts($data) 
    {
        return response([$this->productRepository->allProducts($data)], 200);
    }

    public function validateProductCreate($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
            'price' => 'required|numeric',
            'bar_code' => 'required|max:20|unique:products'
        ]);

        if(!$validator->fails())
        {
            return response([$this->productRepository->createProduct($data)], 200);
        }

        return response([$validator->messages()], 400);
    }

    public function validateProductShow($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|numeric',
        ]);

        if(!$validator->fails())
        {
            return response([$this->productRepository->showProduct($data)], 200);
        }

        return response([$validator->messages()], 400);
    }

    public function validateProductEdit($data)
    {  
        if(count($data) >= 2) 
        {
            $validator = Validator::make($data, [
                'id' => 'required|numeric',
                'price' => 'numeric',
                'bar_code' => 'max:20'
            ]);
        
            if(!$validator->fails())
            {
                return response([$this->productRepository->editProduct($data)], 200);
            }

            return response([$validator->messages()], 400);
        }
        return response(['You have not filled in any data to change!'], 400);
    }

    

    public function validateProductDelete($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|numeric',
        ]);

        if(!$validator->fails())
        {
            return response([$this->productRepository->deleteProduct($data)], 200);
        }
        
        return response([$validator->messages()], 400);
    }
}