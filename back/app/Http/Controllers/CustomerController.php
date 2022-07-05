<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Services\CustomerService;


class CustomerController extends Controller
{
    protected $feeService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {   
        $cpf = $request->input('cpf') ?? null;
        $email = $request->input('email') ?? null;
        $name = $request->input('name') ?? null;
        $data = ['cpf' => $cpf, 'email' => $email, 'name' => $name];
        return $this->customerService->validateCustomerCreate($data);
    }

    
    public function show(Request $request)
    {
        $id = $request->input('id') ?? null;
        $data = ['id' => $id];
        return $this->customerService->validateCustomerShow($data);
    }

    public function edit(Request $request)
    {
        $id = $request->input('id') ?? null;
        $cpf = $request->input('cpf') ?? null;
        $email = $request->input('email') ?? null;
        $name = $request->input('name') ?? null;
        $data = ['id' => $id, 'cpf' => $cpf, 'email' => $email, 'name' => $name];
        $data = array_filter($data);
        return $this->customerService->validateCustomerEdit($data);
    }


    
    public function destroy(Request $request)
    {
        $id = $request->input('id') ?? null;
        $data = ['id' => $id];
        return $this->customerService->validateCustomerDelete($data);
    }
}
