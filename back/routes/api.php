<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/customers', [CustomerController::class, 'index']);
Route::post('/customer/create', [CustomerController::class, 'create']);
Route::post('/customer/edit', [CustomerController::class, 'edit']);
Route::get('/customer/show', [CustomerController::class, 'show']);
Route::post('/customer/delete', [CustomerController::class, 'destroy']);

Route::get('/products', [ProductController::class, 'index']);
Route::post('/product/create', [ProductController::class, 'create']);
Route::post('/product/edit', [ProductController::class, 'edit']);
Route::get('/product/show', [ProductController::class, 'show']);
Route::post('/product/delete', [ProductController::class, 'destroy']);


Route::get('/orders', [OrderController::class, 'index']);
Route::post('/order/create', [OrderController::class, 'create']);
Route::post('/order/edit', [OrderController::class, 'edit']);
Route::get('/order/show', [OrderController::class, 'show']);
Route::post('/order/delete', [OrderController::class, 'destroy']);