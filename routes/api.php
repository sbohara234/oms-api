<?php

use App\Http\Controllers\Order\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthenticationController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('login', [AuthenticationController::class, 'login']);

Route::get('test', [AuthenticationController::class, 'test']);

Route::middleware(['auth:api'])->group(function () {
//     Route::get('/auth-user', 'App\Http\Controllers\V1\Auth\AuthController@authUser');
//     Route::post('/logout', 'App\Http\Controllers\V1\Auth\AuthController@logout');
    // Order ROUTE
    Route::controller(OrderController::class)->prefix('orders')->group(function () {
        Route::get('/', 'index')->name('list-orders');
        Route::PATCH('/{id}/status', 'updateStatus')->name('update-order-status');
        Route::post('/', 'store')->name('create-order');
    });
});


