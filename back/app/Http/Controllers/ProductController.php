<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\ProductService;

class ProductController extends Controller
{
    protected $ProductService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index()
    {
        return $this->productService->validateAllProducts();
    }

    public function create(Request $request)
    {
        $name = $request->input('name') ?? null;
        $price = $request->input('price') ?? null;
        $barCode = $request->input('bar_code') ?? null;
        $data = ['name' => $name, 'price' => $price, 'bar_code' => $barCode];
        return $this->productService->validateProductCreate($data);
    }

    public function show(Request $request)
    {
        $id = $request->input('id') ?? null;
        $data = ['id' => $id];
        return $this->productService->validateProductShow($data);
    }

    public function edit(Request $request)
    {
        $id = $request->input('id') ?? null;
        $name = $request->input('name') ?? null;
        $price = $request->input('price') ?? null;
        $barCode = $request->input('bar_code') ?? null;
        $data = ['id' => $id, 'name' => $name, 'price' => $price, 'bar_code' => $barCode];
        $data = array_filter($data);
        return $this->productService->validateProductEdit($data);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id') ?? null;
        $data = ['id' => $id];
        return $this->productService->validateProductDelete($data);
    }
}
