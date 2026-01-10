<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth;
use App\Http\Controllers\API\User;
use App\Http\Controllers\API\Branch;
use App\Http\Controllers\API\Community;
use App\Http\Controllers\API\Customer;
use App\Http\Controllers\API\GasRequest;
use App\Http\Controllers\API\Sale;
use App\Http\Controllers\API\USSD;
use App\Http\Controllers\API\Driver;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [Auth\LoginController::class, 'index']);

Route::group(['prefix' => 'user'], function () {

    Route::post('/create', [User\UserController::class, 'store']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/', [User\UserController::class, 'index']);
        Route::get('/show/{id}', [User\UserController::class, 'show']);
        Route::post('/update/{id}', [User\UserController::class, 'update']);
    });
});

Route::group(['prefix' => 'branch', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Branch\BranchController::class, 'index']);
    Route::get('/show/{id}', [Branch\BranchController::class, 'show']);
});

Route::group(['prefix' => 'community', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Community\CommunityController::class, 'index']);
    Route::get('/show/{id}', [Community\CommunityController::class, 'show']);
});

Route::group(['prefix' => 'customer', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Customer\CustomerController::class, 'index']);
    Route::get('/show/{id}', [Customer\CustomerController::class, 'show']);
    Route::post('/create', [Customer\CustomerController::class, 'store']);
    Route::post('/update/{id}', [Customer\CustomerController::class, 'update']);
    Route::get('/balance/{id}', [Customer\CustomerController::class, 'balance']);
    Route::get('/request/{id}', [Customer\CustomerController::class, 'customerRequest']);
    Route::get('/statement/{id}', [Customer\CustomerController::class, 'statement']);
    Route::get('/receipt/{id}/{from_date}/{to_date}', [Customer\CustomerController::class, 'receipt']);
});

Route::group(['prefix' => 'gas-request', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [GasRequest\GasRequestController::class, 'index']);
    Route::get('/show/{id}', [GasRequest\GasRequestController::class, 'show']);
    Route::post('/create', [GasRequest\GasRequestController::class, 'store']);
});

Route::group(['prefix' => 'sale', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Sale\SaleController::class, 'index']);
    Route::get('/show/{id}', [Sale\SaleController::class, 'show']);
    Route::post('/create', [Sale\SaleController::class, 'store']);
});

Route::get('/ussd/generate/token', [USSD\USSDController::class, 'generateToken']);

Route::group(['prefix' => 'ussd', 'middleware' => 'auth:api'], function () {
    Route::post('/gas/request/create', [USSD\USSDController::class, 'store']);
    Route::post('/converter', [USSD\USSDController::class, 'convert']);
    Route::get('/customer/balance/{id}', [USSD\USSDController::class, 'balance']);
    Route::get('/customer/check/{id}', [USSD\USSDController::class, 'checkCustomer']);
    Route::post('/payment', [USSD\USSDController::class, 'makePayment']);
    Route::get('/fetch/delivery/branch/{id}', [USSD\USSDController::class, 'fetchDeliveryBranch']);
});

Route::group(['prefix' => 'driver', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [Driver\DriverController::class, 'index']);
    Route::get('/show/{id}', [Driver\DriverController::class, 'show']);
    Route::post('/mark/done/{id}', [Driver\DriverController::class, 'markDone']);
});
