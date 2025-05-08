<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tramita', function (Request $request) {
        return '1';
    });
});


Route::post('/login', [UserController::class, 'login']);
Route::post('/registrar', [UserController::class, 'store']);
