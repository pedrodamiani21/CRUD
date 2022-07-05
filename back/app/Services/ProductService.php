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

    public function validateAllProducts() 
    {
        return response()->json([$this->productRepository->allProducts(), 200]);
    }

    public function validateProductCreate($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
            'price' => 'required|numeric',
            'bar_code' => 'required|max:20|unique:products'
        ]);

        if(!$validator->fails()){
            return response()->json([$this->productRepository->createProduct($data), 200]);
        }
        return response()->json([$validator->messages(), 400]);
    }

    public function validateProductShow($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|numeric',
        ]);

        if(!$validator->fails()){
            return response()->json([$this->productRepository->showProduct($data), 200]);
        }
        return response()->json([$validator->messages(), 400]);
    }

    public function validateProductEdit($data)
    {  
        $validator = Validator::make($data, [
            'id' => 'required|numeric',
            'price' => 'numeric',
            'bar_code' => 'max:20|unique:products'
        ]);
        
        if(!$validator->fails()){
            return response()->json([$this->productRepository->editProduct($data), 200]);
        }
        return response()->json([$validator->messages(), 400]);
    }

    public function validateProductDelete($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|numeric',
        ]);

        if(!$validator->fails()){
            return response()->json([$this->productRepository->deleteProduct($data), 200]);
        }
        return response()->json([$validator->messages(), 400]);
    }
}