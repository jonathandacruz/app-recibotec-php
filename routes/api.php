<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'throttle:10,1'])->group(function () {
   
    Route::prefix('user')->group(function () {
        Route::post('/billing-address', [UserController::class, 'userBillingAddress']);
    });


});


Route::post('/login', [UserController::class, 'login']);
Route::post('/registrar', [UserController::class, 'store']);
