<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('/')->group(function () {
    // Route::post('login', [LoginController::class, 'authenticate'])->name('login');
});
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('/users', [UserController::class, 'store']);
Route::group([
    'middleware' => ['apiJwt']
], function () {
    Route::get('/users', [UserController::class, 'index']);
});
